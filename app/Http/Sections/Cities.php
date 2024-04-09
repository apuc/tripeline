<?php

namespace App\Http\Sections;

use AdminColumn;
use AdminColumnFilter;
use AdminDisplay;
use AdminForm;
use AdminFormElement;
use App\Models\Country;
use App\Models\State;
use Illuminate\Database\Eloquent\Model;
use SleepingOwl\Admin\Contracts\Display\DisplayInterface;
use SleepingOwl\Admin\Contracts\Form\FormInterface;
use SleepingOwl\Admin\Contracts\Initializable;
use SleepingOwl\Admin\Form\Buttons\Cancel;
use SleepingOwl\Admin\Form\Buttons\Save;
use SleepingOwl\Admin\Form\Buttons\SaveAndClose;
use SleepingOwl\Admin\Form\Buttons\SaveAndCreate;
use SleepingOwl\Admin\Section;

/**
 * Class City
 *
 * @property \App\Models\Cities $model
 *
 * @see https://sleepingowladmin.ru/#/ru/model_configuration_section
 */
class Cities extends Section implements Initializable {

    protected $createTitle = "Add City";

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
            AdminColumn::link( 'name', 'Name', 'label' )
                       ->setSearchCallback( function ( $column, $query, $search ) {
                           return $query
                               ->orWhere( 'name', 'like', '%' . $search . '%' )
                               ->orWhere( 'label', 'like', '%' . $search . '%' );
                       } )
            ,
            AdminColumn::text( 'country_code', 'Country code' )
                       ->setOrderable( function ( $query, $direction ) {
                           $query->orderBy( 'order', $direction );
                       } )
                       ->setSearchable( false ),
            AdminColumn::text( 'created_at', 'Created / updated', 'updated_at' )
                       ->setOrderable( function ( $query, $direction ) {
                           $query->orderBy( 'updated_at', $direction );
                       } )
                       ->setSearchable( false )
            ,
        ];

        $display = AdminDisplay::datatables()
                               ->setName( 'firstdatatables' )
                               ->setOrder( [ [ 0, 'asc' ] ] )
                               ->setDisplaySearch( true )
                               ->paginate( 25 )
                               ->setColumns( $columns )
                               ->setHtmlAttribute( 'class', 'table-primary table-hover th-center' );

        $display->setColumnFilters( [
            AdminColumnFilter::select(\App\Models\Country::class, 'name')->setDisplay('name')
                ->setPlaceholder('All countries')
                ->setColumnName('country_id')
            ,
        ] );
        $display->getColumnFilters()->setPlacement( 'card.heading' );

        return $display;
    }

    /**
     * @param int|null $id
     * @param array    $payload
     *
     * @return FormInterface
     */
    public function onEdit( $id = null, $payload = [] ) {
        $form = AdminForm::card()->addBody( [
            AdminFormElement::columns()->addColumn( [
                AdminFormElement::text( 'label', 'Label' )->required()
                ,
                AdminFormElement::text( 'name', 'Name' )->required()
                ,
            ], 'col-xs-12 col-sm-6 col-md-4 col-lg-4' )->addColumn( [

                AdminFormElement::selectajax( 'country_id', 'Country' )
                                ->setModelForOptions( Country::class, 'name' )
                                ->required()
                ,
                AdminFormElement::selectajax( 'state_id', 'State' )
                                ->setModelForOptions( State::class, 'name' )
                                ->required()
            ], 'col-xs-12 col-sm-6 col-md-8 col-lg-8' ),
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
        return false;
    }


    /**
     * @return void
     */
    public function onRestore( $id ) {
        // remove if unused
    }
}
