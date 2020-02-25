<?php

namespace App\Http\Controllers;

use App\Contracts\OccupationParser;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class OccupationsController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    private $occparser;

    public function __construct(OccupationParser $parser)
    {
        $this->occparser = $parser;
    }

    public function index()
    {
        return $this->occparser->list();
    }

    public function serializeArray($arr)
    {
        foreach ($arr as $key => $val) {
            sort($val);
            $arr[$key] = serialize($val);
        }
        return $arr;
    }

    /**
     * 
     */
    public function compare(Request $request)
    {
        $this->occparser->setScope('skills');
        $occupation_1 = $this->occparser->get($request->get('occupation_1'));
        $occupation_2 = $this->occparser->get($request->get('occupation_2'));

        $simalarity = array();
        $difference = array();

        foreach ($occupation_1 as $key => $val) {
            if (in_array($val, $occupation_2)) {
                $simalarity[] = $val;
            } else {
                $difference[] = $val;
            }
        }

        // get the count of occupation_1  
        // get count of similarities between occupation_1 and occupation_2 

        $oc_1_count = count($occupation_1);
        $simalarity_count = count($simalarity);

        // Calucualte percentage based of simalarity 

        $match = round(($simalarity_count / $oc_1_count) * 100);

        return [
            'occupation_1' => $occupation_1,
            'occupation_2' => $occupation_2,
            'match' => $match,
            'simalarity' => $simalarity, // return to display table in frotend
            'difference' => $difference
        ];
    }
}
