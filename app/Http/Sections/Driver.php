<?php

namespace App\Http\Sections;

use AdminColumn;
use AdminColumnFilter;
use AdminDisplay;
use AdminDisplayFilter;
use AdminForm;
use AdminFormElement;
use AdminNavigation;
use App\Models\Cities;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use SleepingOwl\Admin\Contracts\Display\DisplayInterface;
use SleepingOwl\Admin\Contracts\Form\FormInterface;
use SleepingOwl\Admin\Contracts\Initializable;
use SleepingOwl\Admin\Form\Buttons\Cancel;
use SleepingOwl\Admin\Form\Buttons\Save;
use SleepingOwl\Admin\Form\Buttons\SaveAndClose;
use SleepingOwl\Admin\Form\Buttons\SaveAndCreate;
use SleepingOwl\Admin\Section;
use Spatie\LaravelImageOptimizer\Facades\ImageOptimizer;

/*
Class Country
 *
 * @property \App\Models\Country $model
*
 * @see https://sleepingowladmin.ru/#/ru/model_configuration_section
 */
class Driver extends Section implements Initializable {
    /* @var bool */
    protected $checkAccess = false;

    /* @var string */
    protected $title;

    /* @var string */
    protected $alias;

    /* Initialize class.*/
    public function initialize() {
        $this->addToNavigation()->setPriority(121)->setIcon('fas fa-globe');
    }

    /* @param array $payload
     *
     * @return DisplayInterface
     */
    public function onDisplay( $payload = [] ) {
        $columns = [
            AdminColumn::text('id', '#')->setWidth('50px'),
            AdminColumn::text('first_name', 'Name')->setWidth('350px'),
            AdminColumn::text('user.company.name', 'Company')->setWidth('350px'),
            AdminColumn::text('city.name', 'City')->setWidth('350px'),
            AdminColumn::text('country.name', 'Country')->setWidth('350px'),
            AdminColumn::text('state', 'State')->setWidth('350px'),
            AdminColumn::datetime('created_at', 'Date')->setWidth('150px')
        ];


        $display = AdminDisplay::datatables()
            ->setName( 'firstdatatables' )
            ->setOrder( [ [ 0, 'asc' ] ] )
            ->setDisplaySearch( true )
            ->paginate( 25 )
            ->setColumns( $columns )
            ->setHtmlAttribute( 'class', 'table-primary table-hover th-center' );


        $display->setColumnFilters( [
            AdminColumnFilter::select()
                ->setModelForOptions( \App\Models\Driver::class, 'country_id' )
                ->setLoadOptionsQueryPreparer( function ( $element, $query ) {
                    return $query;
                } )
                ->setDisplay( 'country.name' )
                ->setColumnName( 'id' )
                ->setPlaceholder( 'All countries' )
            ,
            AdminColumnFilter::select()
                ->setModelForOptions( \App\Models\Driver::class, 'city_id' )
                ->setLoadOptionsQueryPreparer( function ( $element, $query ) {
                    return $query;
                } )
                ->setDisplay( 'city.name' )
                ->setColumnName( 'id' )
                ->setPlaceholder( 'All cities' )
            ,
            AdminColumnFilter::select()
                ->setOptions( [
                    'Pending time'  => 'Pending time',
                    'Approved' => 'Approved',
                ] )
                ->setLoadOptionsQueryPreparer( function ( $element, $query ) {
                    return $query;
                } )
                ->setDisplay( 'state' )
                ->setColumnName( 'state' )
                ->setPlaceholder( 'All states' )
            ,
        ] );

        $display->getColumnFilters()->setPlacement( 'card.heading' );

        return $display;
    }

    /* @param int|null $id
     * @param array    $payload
     *
     * @return FormInterface
     */
    public function onEdit( $id = null, $payload = []) {
        $form = AdminForm::card()->addBody( [
            AdminFormElement::columns()->addColumn( [
                AdminFormElement::text('first_name', 'First name')
            ], 'col-xs-12 col-sm-6 col-md-2 col-lg-2' )->addColumn( [
                AdminFormElement::text('last_name', 'Last name')->required()
            ],'col-xs-12 col-sm-6 col-md-2 col-lg-2' )->addColumn( [
                AdminFormElement::text('phone', 'Phone')->required()
            ],'col-xs-12 col-sm-6 col-md-2 col-lg-2' )->addColumn( [
                AdminFormElement::text('email', 'Email')->required()
//            ], 'col-xs-12 col-sm-6 col-md-2 col-lg-2' )->addColumn( [
//                AdminFormElement::dependentselect( 'city_id', 'City' )
//                    ->setModelForOptions( Cities::class, 'name' )
//                    ->required(),
            ], 'col-xs-12 col-sm-6 col-md-2 col-lg-2' )->addColumn( [
                AdminFormElement::dependentselect( 'country_id', 'Country' )
                    ->setModelForOptions( Country::class, 'name' )
                    ->required(),
            ], 'col-xs-12 col-sm-6 col-md-4 col-lg-4' )->addColumn( [
                AdminFormElement::radio( 'state', 'State' )
                    ->setEnum( [
                        'Pending time'   => 'Pending time',
                        'Approved' => 'Approved',
                    ] )
                    ->required()
            ], 'col-xs-12 col-sm-3 col-md-3 col-lg-3' )->addColumn( [
                //                AdminFormElement::image( 'photo', 'Image' )
//                    ->required()
//                    ->setAfterSaveCallback(function ($value, $model) {
//                        if ($value) {
//                            $map = collect($value)->map(function ($item) {
//                                ImageOptimizer::optimize($item);
//                            });
//                        }
//                    })
//                ,
                AdminFormElement::custom()->setDisplay(function(Model $model) {
                    return "<div class='photo__driver'><div class='photos__item'><img src='" . $model->photo . "'></div></div>";
                })
            ], 'col-xs-12 col-sm-3 col-md-3 col-lg-3' )->addColumn( [
                AdminFormElement::custom()->setDisplay(function(Model $model) {
                    $company = $model->user->company->name ?? '';
                    $html = "<div><span><b>Company</b></span><br><span>" . $company ."</span></div>";
                    return $html;
                })
            ], 'col-xs-12 col-sm-6 col-md-6 col-lg-6' )
        ] );

//        dump(Driver::find($id));
////        dump($form->getElement('email'));
//        dump($form->getModel());
//        dump(get_class_methods($form));

//        $user = User::create([
//            'email'    => $request->partner['email'],
//            'phone'    => $request->partner['phone'],
//            'password' => Hash::make($pass),
//            'is_admin' => 0,
//            'role_id'  => 5, //Partner - 4, driver - 5, travel agency - 6
//        ]);
//
//        EmailHelper::sendEmailFromRegPartner($partnerData);

        $form->getButtons()->setButtons( [
            'save'            => new Save(),
            'save_and_close'  => new SaveAndClose(),
            'save_and_create' => new SaveAndCreate(),
            'cancel'          => ( new Cancel() ),
        ] );

        return $form;
    }

    /* @return FormInterface */
    public function onCreate( $payload = [] ) {
        return $this->onEdit( null, $payload );
    }

    /* @return bool */
    public function isDeletable( Model $model ) {
        return false;
    }

    /* @return void */
    public function onRestore( $id ) {
        // remove if unused
    }

    public function updated( Model $model ) {
        dump($model);
    }
}
