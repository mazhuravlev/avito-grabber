<?php

namespace App\Http\Controllers;

use App\Offer;

use App\Http\Requests;

class OffersController extends Controller
{

    public function offers()
    {
        $offers = Offer::query()->paginate(40);
        return view('offers')->with(
            ['offers' => $offers]
        );
    }

    public function offer(Offer $offer)
    {
        $offer->load(['phones', 'grabbedLink', 'photos']);
        return view('offer')->with([
            'offer' => $offer
        ]);
    }

}
