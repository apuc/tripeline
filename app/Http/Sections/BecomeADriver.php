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
class BecomeADriver extends Section implements Initializable {
    /* @var bool */
    protected $checkAccess = false;

    /* @var string */
    protected $title;

    /* @var string */
    protected $alias;

    /* Initialize class.*/
    public function initialize() {

    }

    /* @param array $payload
     *
     * @return DisplayInterface
     */
    public function onDisplay( $payload = [] ) {
        $columns = [];

        $display = AdminDisplay::table()
            ->setApply(function ($query) {
                $query->where('role_id', 5);
            })
            ->setColumns(
                AdminColumn::text('profile.first_name', 'Name'),
                AdminColumn::text('profile.city', 'City'),
                AdminColumn::text('profile.english_lvl', 'English level'),
                AdminColumn::text('phone', 'Phone'),
                AdminColumn::text('email', 'Email'),
                AdminColumn::datetime('created_at', 'Date')->setWidth('150px')
            )->paginate(25);

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
                AdminFormElement::text('profile.first_name', 'Name')
            ], 'col-xs-12 col-sm-6 col-md-2 col-lg-2' )->addColumn( [
                AdminFormElement::dependentselect( 'profile.city', 'From City' )
                    ->setModelForOptions( Cities::class, 'name' )
                    ->required(),
            ], 'col-xs-12 col-sm-6 col-md-2 col-lg-2' )->addColumn( [
                AdminFormElement::text('phone', 'Phone')->required()
            ], 'col-xs-12 col-sm-6 col-md-2 col-lg-2' )->addColumn( [
                AdminFormElement::text('email', 'Email')->required()
            ], 'col-xs-12 col-sm-6 col-md-2 col-lg-2' )->addColumn( [
                AdminFormElement::radio( 'status', 'Status' )
                    ->setEnum( [
                        'new'   => 'new',
                        'checking' => 'checking',
                        'confirmed' => 'confirmed',
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
