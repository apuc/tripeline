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
class Vehicle extends Section implements Initializable {
    /* @var bool */
    protected $checkAccess = false;

    /* @var string */
    protected $title;

    /* @var string */
    protected $alias;

    /* Initialize class.*/
    public function initialize() {
        $this->addToNavigation()->setPriority(120)->setIcon('fas fa-globe');
    }

    /* @param array $payload
     *
     * @return DisplayInterface
     */
    public function onDisplay( $payload = [] ) {

        $columns = [
            AdminColumn::text('id', '#')->setWidth('50px'),
            AdminColumn::text('mark', 'Mark')->setWidth('350px'),
            AdminColumn::text('model', 'Model')->setWidth('350px'),
            AdminColumn::text('user.company.name', 'Company')->setWidth('350px'),
            AdminColumn::text('year', 'Year')->setWidth('200px'),
            AdminColumn::text('class', 'Class')->setWidth('350px'),
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
                    ->setModelForOptions( \App\Models\Vehicle::class, 'mark' )
                    ->setLoadOptionsQueryPreparer( function ( $element, $query ) {
                        return $query;
                    } )
                    ->setDisplay( 'mark' )
                    ->setColumnName( 'id' )
                    ->setPlaceholder( 'All marks' )
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
                AdminColumnFilter::select()
                    ->setModelForOptions( \App\Models\Vehicle::class, 'class' )
                    ->setLoadOptionsQueryPreparer( function ( $element, $query ) {
                        return $query;
                    } )
                    ->setDisplay( 'class' )
                    ->setColumnName( 'id' )
                    ->setPlaceholder( 'All classes' )
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
                AdminFormElement::text('mark', 'Mark')
            ], 'col-xs-12 col-sm-6 col-md-2 col-lg-2' )->addColumn( [
                AdminFormElement::text('model', 'Model')->required()
            ],'col-xs-12 col-sm-6 col-md-2 col-lg-2' )->addColumn( [
                AdminFormElement::text('year', 'Year')->required()
            ],'col-xs-12 col-sm-6 col-md-2 col-lg-2' )->addColumn( [
                AdminFormElement::text('licence', 'Licence')->required()
            ], 'col-xs-12 col-sm-6 col-md-2 col-lg-2' )->addColumn( [
                AdminFormElement::text('color', 'Color')->required()
            ], 'col-xs-12 col-sm-6 col-md-2 col-lg-2' )->addColumn( [
                AdminFormElement::text('class', 'Service')->required()
            ],'col-xs-12 col-sm-6 col-md-4 col-lg-3' )->addColumn( [
                AdminFormElement::custom()->setDisplay(function(Model $model) {
                    $company = $model->user->company->name ?? '';
                    $html = "<div><span><b>Company</b></span><br><span>" . $company ."</span></div>";
                    return $html;
                })
            ], 'col-xs-12 col-sm-6 col-md-4 col-lg-3' )->addColumn( [
                AdminFormElement::custom()->setDisplay(function(Model $model) {
                    $html = "<div class='photo photos__car photo__active'><div class='photos__list'>";
                    foreach ($model->photos as $photo) {
                        $html .= "<div class='photos__item'><img src='" . $photo->photo . "'></div>";
                    }
                    $html .= "</div></div>";
                    return $html;
                })
            ], 'col-xs-12 col-sm-6 col-md-2 col-lg-2' )->addColumn( [
                AdminFormElement::radio( 'state', 'State' )
                    ->setEnum( [
                        'Pending time'   => 'Pending time',
                        'Approved' => 'Approved',
                    ] )
                    ->required()
            ]),
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

    /* @return bool */
    public function isDeletable( Model $model ) {
        return false;
    }

    /* @return void */
    public function onRestore( $id ) {
        // remove if unused
    }
}
