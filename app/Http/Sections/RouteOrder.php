<?php

namespace App\Http\Sections;

use AdminColumn;
use AdminColumnFilter;
use AdminDisplay;
use AdminForm;
use AdminFormElement;
use App\Models\Cities;
use App\Models\Country;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use SleepingOwl\Admin\Contracts\Display\DisplayInterface;
use SleepingOwl\Admin\Contracts\Form\FormInterface;
use SleepingOwl\Admin\Contracts\Initializable;
use SleepingOwl\Admin\Form\Buttons\Cancel;
use SleepingOwl\Admin\Form\Buttons\Save;
use SleepingOwl\Admin\Form\Buttons\SaveAndClose;
use SleepingOwl\Admin\Form\Buttons\SaveAndCreate;
use SleepingOwl\Admin\Section;
use App\Helper\NotificationHelper;

/**
 * Class Country
 *
 * @property \App\Models\Country $model
 *
 * @see https://sleepingowladmin.ru/#/ru/model_configuration_section
 */
class RouteOrder extends Section implements Initializable {
    /**
     * @var bool
     */
    protected $checkAccess = false;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $alias;

    /**
     * Initialize class.
     */
    public function initialize() {
        $this->addToNavigation()->setPriority( 120 )->setIcon( 'fas fa-globe' );
    }

    /**
     * @param array $payload
     *
     * @return DisplayInterface
     */
    public function onDisplay( $payload = [] ) {
        $columns = [
            AdminColumn::text( 'id', '#' )->setWidth( '50px' )->setHtmlAttribute( 'class', 'text-center' ),
            AdminColumn::custom( 'Route', function ( $model ) {
                $route = $model->getRoute();

                return $route->title;
            } ),
            AdminColumn::custom( 'Vehicles', function ( $model ) {
                $cars      = $model->getCars();
                $carsLists = [];
                $carsList  = $cars->get();
                foreach ( $carsList as $key => $car ) {
                    $carsLists[] = $car->title;
                }

                return implode( ',', $carsLists );
            } ),
            AdminColumn::custom( 'Status', function ( $model ) {
                $color = $model->status === 'complete' ? 'green' : 'orange';

                return "<span style='color: " . $color . "'>$model->status</span>";
            } ),
            AdminColumn::text( 'amount', 'Price' )
                       ->setOrderable( function ( $query, $direction ) {
                           $query->orderBy( 'status', $direction );
                       } )
                       ->setSearchable( false ),
            AdminColumn::text( 'created_at', 'Created / updated', 'updated_at' )
                       ->setOrderable( function ( $query, $direction ) {
                           $query->orderBy( 'updated_at', $direction );
                       } )
                       ->setSearchable( false )
            ,
        ];

    //    dd(\App\Models\RouteOrder::find(19));

        $display = AdminDisplay::datatables()
                               ->setName( 'firstdatatables' )
                               ->setOrder( [ [ 0, 'asc' ] ] )
                               ->setDisplaySearch( true )
                               ->paginate( 25 )
                               ->setColumns( $columns )
                               ->setHtmlAttribute( 'class', 'table-primary table-hover th-center' );

        $display->setColumnFilters( [
            AdminColumnFilter::select()
                             ->setModelForOptions( \App\Models\Routes::class, 'title' )
                             ->setLoadOptionsQueryPreparer( function ( $element, $query ) {
                                 return $query;
                             } )
                             ->setDisplay( 'title' )
                             ->setColumnName( 'route_id' )
                             ->setPlaceholder( 'All names' )
            ,
            AdminColumnFilter::select()
                             ->setOptions( [
                                 'pending'  => 'Pending',
                                 'complete' => 'Complete',
                                 'fail'     => 'Fail'
                             ] )
                             ->setLoadOptionsQueryPreparer( function ( $element, $query ) {
                                 return $query;
                             } )
                             ->setDisplay( 'status' )
                             ->setColumnName( 'status' )
                             ->setPlaceholder( 'All statuses' )
            ,
        ] );
        $display->getColumnFilters()->setPlacement( 'card.heading' );


        $display->setApply(function ($query)
        {
            $query->where('deleted_at', '=', null);
        });

        return $display;
    }

    /**
     * @param int|null $id
     * @param array    $payload
     *
     * @return FormInterface
     */
    public function onEdit( $id = null, $payload = [] ) {
        //dump($id);
        $form = AdminForm::card()->addBody( [
            AdminFormElement::columns()->addColumn( [
                AdminFormElement::hidden( 'user_id' )->setDefaultValue( Auth::id() ),
                AdminFormElement::custom()
                                ->setDisplay( function ( $instance ) {

                                    $route = $instance->getRoute();
                                    $title = $route['title'];
                                    $from  = $instance->getCity( $route['route_from_city_id'] )['label'];
                                    $to    = $instance->getCity( $route['route_to_city_id'] )['label'];

                                    return
                                        "
                                        <ul>
                                        <li>Route: " . json_encode( $title ) . "</li>
                                        <li>From: $from</li>
                                        <li>To: $to</li>
                                        </ul>
                                     ";
                                } )
                                ->setCallback( function ( $instance ) {
                                } ),
                AdminFormElement::text( 'first_name', 'First name' )->setReadonly( true ),
                AdminFormElement::text( 'last_name', 'Last name' )->setReadonly( true ),
                AdminFormElement::text( 'email', 'Email' )->setReadonly( true ),
                AdminFormElement::text( 'phone', 'Phone' )->setReadonly( true ),
                AdminFormElement::text( 'id', 'Booking number' )->setReadonly( true ),
                AdminFormElement::text( 'driver.first_name', 'Driver' )->setReadonly( true ),
                AdminFormElement::text( 'vehicle.licence', 'Vehicle' )->setReadonly( true ),
            ], 'col-xs-12 col-sm-6 col-md-4 col-lg-4' )->addColumn( [
                AdminFormElement::custom()
                                ->setDisplay( function ( $instance ) {
                                    $places     = $instance->places();
                                    $placesList = '';
                                    foreach ( $places->get() as $place ) {
                                        $placesList .= "<li>" . $place->title_en . " --- " . (int) $place->pivot->durations . "min.</li>";
                                    }

                                    return
                                        "<b>Places:</b>
                                        <ul>
                                        $placesList
                                        </ul>
                                     ";
                                } )
                                ->setCallback( function ( $instance ) {
                                } ),
                AdminFormElement::custom()
                                ->setDisplay( function ( $instance ) {
                                    $cars      = $instance->getCars();
                                    $carsLists = '';
                                    $carsList  = $cars->get();
                                    foreach ( $carsList as $key => $car ) {
                                        $carsLists .= "<li>" . $car->title . "</li>";

                                    }

                                    return
                                        "<b>Cars list:</b>
                                        <ul>
                                        $carsLists
                                        </ul>
                                     ";
                                } )
                                ->setCallback( function ( $instance ) {
                                } ),
                AdminFormElement::datetime( 'route_date', 'Route date' )
                                ->required(),
                AdminFormElement::columns()->addColumn(
                    [
                        AdminFormElement::text( 'adults', 'Adults' )
                                        ->required()
                    ], 'col-xs-12 col-sm-6 col-md-4 col-lg-4' )->addColumn(
                    [
                        AdminFormElement::text( 'childrens', 'Childrens' )
                                        ->required()
                    ], 'col-xs-12 col-sm-6 col-md-4 col-lg-4' )->addColumn(
                    [
                        AdminFormElement::text( 'luggage', 'Luggage' )
                                        ->required()
                    ], 'col-xs-12 col-sm-6 col-md-4 col-lg-4' ),
            ], 'col-xs-12 col-sm-6 col-md-4 col-lg-4' )->addColumn( [
                AdminFormElement::textarea( 'comment', 'Comment' )
                                ->required(),
                AdminFormElement::text( 'pickup_address', 'Pickup address' )
                                ->required(),
                AdminFormElement::text( 'drop_off_address', 'Drop off address' )
                                ->required(),
                AdminFormElement::number( 'amount', 'Amount' )
                                ->required()->setReadonly( true ),
                AdminFormElement::number( 'currency', 'Currency' )
                                ->required()->setReadonly( true ),
                AdminFormElement::custom()
                                ->setDisplay( function ( $instance ) {

                                    $instance->payment_type;
                                    $payment_type = $instance->payment_type === 1 ? 'Cart' : 'Cash';

                                    return
                                        "<b>Payment type: " . $payment_type . "</b>";
                                } )
                                ->setCallback( function ( $instance ) {
                                } ),
//                AdminFormElement::radio( 'status', 'Status' )
//                                ->setOptions( [
//                                    'pending'  => 'Pending',
//                                    'complete' => 'Complete',
//                                    'fail'     => 'Fail'
//                                ] )
//                                ->required(),
                AdminFormElement::custom()
                    ->setDisplay( function ( $instance ) {
                        //$payment_type = $instance->payment_type === 1 ? 'Cart' : 'Cash';
                        $statuses = ['complete', 'fail', 'pending', 'planned'];
                        $html = "<div class='form-group form-element-radio'><label for='status' class='control-label required'>Status
                            <span class='form-element-required'>*</span></label>";

                        foreach ($statuses as $status) {
                            $checked = $instance->status == $status ? "checked='checked'" : '';
                            $html .= "<div class='radio'><label><input type='radio' " . $checked . " name='status' value='" . $status . "'>" . $status . "</label></div>";
                        }

                        $html .= "</div>";

                        return $html;
                    } )
                    ->setCallback( function ($instance, $model) {
                        if ($instance->driver) {
                            if ($model->status === 'planned' && $instance->status !== 'planned' && $instance->driver->user->device) {
                                NotificationHelper::send($instance->driver->user->device->token, 'Mytripline Driver', "You've been assigned a ride. Click here for details.");
                            }
                        }

                        $instance->update([
                            'status' => $model->status
                        ]);
                    } )
            ], 'col-xs-12 col-sm-6 col-md-4 col-lg-4' )
        ] );
        $form->getButtons()->setButtons( [
            'save'            => new Save(),
            'save_and_close'  => new SaveAndClose(),
            'save_and_create' => new SaveAndCreate(),
            'cancel'          => ( new Cancel() ),
        ] );

        return $form;
    }

    /**
     * @return FormInterface
     */
    public function onCreate( $payload = [] ) {
        return $this->onEdit( null, $payload );
    }

    /**
     * @return bool
     */
    public function isDeletable( Model $model ) {
        return Auth::user()->isAdmin();

    }


    /**
     * @return bool
     */
    public function isCreatable() {
        return false;
    }

    /**
     * @return void
     */
    public function onRestore( $id ) {
        // remove if unused
    }
}
