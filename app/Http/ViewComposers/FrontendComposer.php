<?php

namespace App\Http\ViewComposers;
use App\Models\Competition;
use Illuminate\View\View;

class FrontendComposer
{
    public function compose(View $view){
        $has_accept_cookies = 0;
        if (isset($_COOKIE['openfed_rgpd'])) {
            $has_accept_cookies = 1;
        }

        // on récupère des informations sur le concours actuel
        $competition = Competition::where('type', 4)->orderByDesc('saison')->first();

        $view->with('cookie_rgpd', $has_accept_cookies)->with('saison', $competition->saison);
    }
}
