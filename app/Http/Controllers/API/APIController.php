<?php

namespace roombooker\Http\Controllers\API;

use Illuminate\Http\Request;
use roombooker\Http\Controllers\Controller;
use roombooker\Room;
use roombooker\Booking;
use roombooker\Signature;
use Carbon\Carbon;

class APIController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Return rooms in building as json
     *
     * @param Request $request
     * @return string
     */
    public function roomsInBuilding(Request $request)
    {
        $b_id = $request->input('b_id');
        return response()->json(Room::where('building_id', $b_id)->orderBy('name', 'asc')->get(), 200);
    }

    public function roomDetail(Request $request)
    {
        $r_id = $request->input('r_id');
        $room = Room::findOrFail($r_id);
        $room['facilities'] = $room->facilities;
        return response()->json($room, 200);
    }

    public function generateAccessCode(Request $request)
    {
        $user_id = $request->user()->id;
        $booking_id = $request->input('bid');
        $booking = Booking::whereHas('details', function($query) use($user_id) {
            $query->where('booker_id', $user_id);
        })->findOrFail($booking_id);
        $booking->access_code = self::rand_chars("ABCDEFGHJKLMNPQRSTUVWXY3456789", 6);
        $booking->code_expiry = Carbon::now()->addHour();
        $booking->save();
        return response()->json([
            'code' => $booking->access_code,
            'expiry' => $booking->code_expiry->timestamp,
        ], 200);
    }

    public function accessBooking(Request $request)
    {
        if(!$request->user()->is_authority) {
            return response('Unauthorized', 403);
        } else {
            $code = $request->input('code');
            $booking = Booking::where('access_code', $code)
                            ->where('code_expiry', '>=', Carbon::now())
                            ->where('status', Booking::INCOMPLETE_STATUS)
                            ->with('details')
                            ->firstOrFail();
            $booking['details']['start'] = $booking->details->start_datetime->format('d/m/Y H:i');
            $booking['details']['end'] = $booking->details->end_datetime->format('d/m/Y H:i');
            $booking['details']['facilities'] = $booking->details->facilities;
            $booking['details']['room'] = $booking->details->room;
            $booking['details']['building'] = $booking->details->room->building;
            return response()->json($booking, 200);
        }
    }

    public function sign(Request $request)
    {
        if(!$request->user()->is_authority) {
            return response('Unauthorized', 403);
        } else {
            $bid = $request->input('bid');
            $booking = Booking::findOrFail($bid);
            $booking->status = Booking::ACKNOWLEDGED_STATUS;
            $booking->save();

            $signature = new Signature;
            $signature->signee_id = $request->user()->id;
            $signature->booking_id = $booking->id;
            $signature->save();

            return response()->json(['status' => $signature->is_valid], 200);
        }
    }

    /**
     * Generate random string
     *
     * @param string $chars
     * @param int $length
     * @param boolean $unique
     * @return string
     */
    private static function rand_chars($chars, $length, $unique = FALSE) {
        if (!$unique)
            for ($s = '', $i = 0, $z = strlen($chars)-1; $i < $length; $x = random_int(0,$z), $s .= $chars{$x}, $i++);
        else
            for ($i = 0, $z = strlen($chars)-1, $s = $chars{random_int(0,$z)}, $i = 1; $i != $length; $x = random_int(0,$z), $s .= $chars{$x}, $s = ($s{$i} == $s{$i-1} ? substr($s,0,-1) : $s), $i=strlen($s));
        return $s;
    }
}
