<?php

namespace App\Http\Sections;

use AdminColumn;
use AdminColumnFilter;
use AdminDisplay;
use AdminDisplayFilter;
use AdminForm;
use AdminFormElement;
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
use Spatie\LaravelImageOptimizer\Facades\ImageOptimizer;
use App\Models\RouteCar;

/*
Class Country
 *
 * @property \App\Models\Country $model
*
 * @see https://sleepingowladmin.ru/#/ru/model_configuration_section
 */
class Car extends Section implements Initializable {
    /* @var bool */
    protected $checkAccess = false;

    /* @var string */
    protected $title;

    /* @var string */
    protected $alias;

    /* Initialize class.*/
    public function initialize() {
        $this->addToNavigation()->setPriority( 120 )->setIcon( 'fas fa-globe' );
    }

    /* @param array $payload
     *
     * @return DisplayInterface
     */
    public function onDisplay( $payload = [] ) {
        $columns = [
            AdminColumn::text( 'id', '#' )->setWidth( '50px' )->setHtmlAttribute( 'class', 'text-center' ),
            AdminColumn::link( 'title', 'Name', 'created_at' )
                ->setSearchCallback( function ( $column, $query, $search ) {
                    return $query
                        ->orWhere( 'title', 'like', '%' . $search . '%' )
                        ->orWhere( 'created_at', 'like', '%' . $search . '%' );
                } ),
            AdminColumn::text( 'places_min', 'Min seats' )
                ->setOrderable( function ( $query, $direction ) {
                    $query->orderBy( 'places_min', $direction );
                } )
                ->setSearchable( false ),
            AdminColumn::text( 'places_max', 'Max seats' )
                ->setOrderable( function ( $query, $direction ) {
                    $query->orderBy( 'places_max', $direction );
                } )
                ->setSearchable( false ),
            AdminColumn::text( 'luggage', 'Luggage' )
                ->setOrderable( function ( $query, $direction ) {
                    $query->orderBy( 'luggage', $direction );
                } )
                ->setSearchable( false ),
            AdminColumn::text( 'price', 'Car price' )
                ->setOrderable( function ( $query, $direction ) {
                    $query->orderBy( 'price', $direction );
                } )
                ->setSearchable( false ),
            AdminColumn::text( 'priority', 'Priority order' ),
            AdminColumn::text( 'vehicle.title', 'Vehicle Body Type' )->setSearchable( false ),
            AdminColumn::text( 'created_at', 'Created / updated', 'updated_at' )
                ->setOrderable( function ( $query, $direction ) {
                    $query->orderBy( 'created_at', $direction );
                } )
                ->setSearchable( false ),
        ];

        $display = AdminDisplay::datatables()
            ->setName( 'firstdatatables' )
            ->setOrder( [ [ 0, 'asc' ] ] )
            ->setDisplaySearch( true )
            ->paginate( 25 )
            ->setColumns( $columns )
            ->setHtmlAttribute( 'class', 'table-primary table-hover th-center' );

//        $display->setFilters(
//            AdminDisplayFilter::field('brand')->setTitle('test')
//        )->setColumns(
//            AdminColumn::link('brand', 'Brand'),
//        )->paginate(20);

        //dd(\App\Models\Car::all());

        $display->setColumnFilters( [
            AdminColumnFilter::select(\App\Models\Car::class, 'brand')->setDisplay('brand')
                ->setPlaceholder('All brands')
            ,
        ] );


//        $display->setFilters(
//            AdminDisplayFilter::field('brand')->setTitle('test')
//        );

//        $display->setColumnFilters( [
//            AdminDisplayFilter::field('brand')->setTitle('test')
//            ,
//        ] );
        $display->getColumnFilters()->setPlacement( 'card.heading' );

        return $display;
    }

    /* @param int|null $id
     * @param array    $payload
     *
     * @return FormInterface
     */
    public function onEdit( $id = null, $payload = [] ) {
        $form = AdminForm::card()->addBody( [
            AdminFormElement::columns()->addColumn( [
                AdminFormElement::text( 'title', 'Title' )
            ], 'col-xs-12 col-sm-6 col-md-8 col-lg-8' )->addColumn( [
                AdminFormElement::select( 'vehicle_body_type', 'Vehicle Body Type' )
                    ->setModelForOptions( \App\Models\VehicleBodyType::class, 'title' )
                    ->required(),
            ], 'col-xs-12 col-sm-6 col-md-2 col-lg-2' )->addColumn( [
                AdminFormElement::number( 'priority', 'Priority' )
                    ->required(),
            ], 'col-xs-12 col-sm-6 col-md-2 col-lg-2' )->addColumn( [
                AdminFormElement::text( 'luggage', 'Luggage' )
                    ->required(),
            ], 'col-xs-12 col-sm-6 col-md-2 col-lg-2' )->addColumn( [
                AdminFormElement::text( 'places_min', 'Min seats' )
                    ->required(),
            ], 'col-xs-12 col-sm-6 col-md-2 col-lg-2' )->addColumn( [
                AdminFormElement::text( 'places_max', 'Max seats' )
                    ->required(),
            ], 'col-xs-12 col-sm-6 col-md-2 col-lg-2' )->addColumn( [
                AdminFormElement::text( 'price', 'Car price' )
                    ->required(),
            ], 'col-xs-12 col-sm-6 col-md-4 col-lg-3' )->addColumn( [
                AdminFormElement::image( 'image', 'Image' )
                    ->required()
                    ->setAfterSaveCallback(function ($value, $model) {
                        if ($value) {
                            $map = collect($value)->map(function ($item) {
                                ImageOptimizer::optimize($item);
                            });
                        }
                    })
                ,
                AdminFormElement::html( "<b>Only png format.</b>"),
            ], 'col-xs-12 col-sm-4 col-md-4 col-lg-4' )->addColumn( [
                AdminFormElement::wysiwyg( 'note', 'Note', 'ckeditor' )
                    ->required(),
            ], 'col-xs-12 col-sm-8 col-md-8 col-lg-8' ),
        ] );

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

    /**
     * @return bool
     */
    public function isDeletable( Model $model ) {
        //dump($model);
        if (RouteCar::where('car_id', $model->id)->first()) {
            return false;
        }
        return Auth::user()->isAdmin(); //Auth::user()->isAdmin()
    }

    /* @return void */
    public function onRestore( $id ) {
        // remove if unused
    }

//    public function onDelete( $id ) {
//        if (RouteCar::where('car_id', $id)->first()) {
//            return false;
//        }
//        return true; //Auth::user()->isAdmin()
//    }
}
