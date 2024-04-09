<?php

namespace App\Providers;

use AdminNavigation;
use SleepingOwl\Admin\Navigation\Page;
use SleepingOwl\Admin\Providers\AdminSectionsServiceProvider as ServiceProvider;

class AdminSectionsServiceProvider extends ServiceProvider
{

    /**
     * @var array
     */
    protected $sections = [
        \App\Models\OurTeam::class => 'App\Http\Sections\OurTeam',
        \App\Models\User::class => 'App\Http\Sections\Users',
        \App\Models\Customers::class => 'App\Http\Sections\Customers',
        \App\Models\Page::class => 'App\Http\Sections\Page',
//        \App\Models\BlogPost::class => 'App\Http\Sections\BlogPost',
        \App\Models\Role::class => 'App\Http\Sections\Role',
        \App\Models\Country::class => 'App\Http\Sections\Country',
//        \App\Models\Language::class => 'App\Http\Sections\Language',
        \App\Models\Car::class => 'App\Http\Sections\Car',
        \App\Models\VehicleBodyType::class => 'App\Http\Sections\VehicleBodyType',
        \App\Models\Routes::class => 'App\Http\Sections\Routes',
        \App\Models\Partner::class => 'App\Http\Sections\Partner',
        \App\Models\Content::class => 'App\Http\Sections\Content',
        \App\Models\Place::class => 'App\Http\Sections\Place',
        \App\Models\ExchangeRate::class => 'App\Http\Sections\ExchangeRate',
        \App\Models\Cities::class => 'App\Http\Sections\Cities',
        \App\Models\Currency::class => 'App\Http\Sections\Currency',
        \App\Models\RouteOrder::class => 'App\Http\Sections\RouteOrder',
        \App\Models\BecomeAPartner::class => 'App\Http\Sections\BecomeAPartner',
        \App\Models\BecomeADriver::class => 'App\Http\Sections\BecomeADriver',
        \App\Models\BecomeATravelAgency::class => 'App\Http\Sections\BecomeATravelAgency',
        \App\Models\Vehicle::class => 'App\Http\Sections\Vehicle',
        \App\Models\Driver::class => 'App\Http\Sections\Driver',
        \App\Models\Message::class => 'App\Http\Sections\Message',
    ];

    /**
     * Register sections.
     *
     * @param \SleepingOwl\Admin\Admin $admin
     * @return void
     */
    public function boot(\SleepingOwl\Admin\Admin $admin)
    {
    	//

        parent::boot($admin);

        $this->registerNavigation();
    }

    private function registerNavigation()
    {
        AdminNavigation::setFromArray([
            [
                'title' => 'Fleets',
                'icon' => 'fas fa-users',
                'priority' => 500,
                'pages' => [
                    (new Page(\App\Models\BecomeAPartner::class))->setPriority(0),
                    (new Page(\App\Models\BecomeADriver::class))->setPriority(0),
                    (new Page(\App\Models\BecomeATravelAgency::class))->setPriority(0),
                ],
            ]
        ]);
    }
}
