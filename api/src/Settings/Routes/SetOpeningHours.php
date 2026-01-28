<?php

namespace App\Settings\Routes;

use App\Auth\Permissions\RequiresAdminPermission;
use App\Settings\Models\OpeningHours;
use App\Settings\Requests\OpeningHourRequest;
use Neoan\Routing\Attributes\Put;
use Neoan\Routing\Interfaces\Routable;

#[Put('/api/settings/opening-hours', RequiresAdminPermission::class)]
class SetOpeningHours implements Routable
{
    public function __invoke(OpeningHourRequest $request): array
    {
        $hours = OpeningHours::retrieveOne([]) ?? new OpeningHours();

        // clean up old junk
        $oneMonthAgo = strtotime('-1 month');
        $request->blockoutDays = array_values(array_filter($request->blockoutDays, function($item) use ($oneMonthAgo) {
            return strtotime($item['id']) > $oneMonthAgo;
        }));

        $request->customHoursCafe = array_values(array_filter($request->customHoursCafe, function($item) use ($oneMonthAgo) {
            return strtotime($item['id']) > $oneMonthAgo;
        }));
        $request->customHoursLounge = array_values(array_filter($request->customHoursLounge, function($item) use ($oneMonthAgo) {
            return strtotime($item['id']) > $oneMonthAgo;
        }));


        // store
        $hours->openingHoursCafe = $request->asJson($request->openingHoursCafe);
        $hours->openingHoursLounge = $request->asJson($request->openingHoursLounge);
        $hours->blockoutDays = $request->asJson($request->blockoutDays);
        $hours->customHoursCafe = $request->asJson($request->customHoursCafe);
        $hours->customHoursLounge = $request->asJson($request->customHoursLounge);

        return $hours->store()->asJson();

    }


}