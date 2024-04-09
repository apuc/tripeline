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
class BecomeAPartner extends Section implements Initializable {
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
                //dd($query->find(192));
                $query->where('role_id', 4);
            })
            ->setColumns(
                AdminColumn::text('profile.first_name', 'Name'),
                AdminColumn::text('profile.city', 'City'),
                AdminColumn::text('profile.english_lvl', 'English level'),
                AdminColumn::text('phone', 'Phone'),
                AdminColumn::text('email', 'Email'),
                AdminColumn::text('status', 'Status'),
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
                AdminFormElement::html('<div><b>Profile</b><hr></div>')
            ], 'col-xs-12 col-sm-12 col-md-12 col-lg-12' )->addColumn( [
                AdminFormElement::text('profile.first_name', 'First Name')
            ], 'col-xs-12 col-sm-6 col-md-2 col-lg-2' )->addColumn( [
                AdminFormElement::text('profile.last_name', 'Last Name')
            ], 'col-xs-12 col-sm-6 col-md-2 col-lg-2' )->addColumn( [
                AdminFormElement::text('profile.whatsapp', 'Whatsapp')
            ], 'col-xs-12 col-sm-6 col-md-2 col-lg-2' )->addColumn( [
                AdminFormElement::dependentselect( 'profile.country', 'From Country' )
                    ->setModelForOptions( Country::class, 'name' )
            ], 'col-xs-12 col-sm-6 col-md-2 col-lg-2' )->addColumn( [
                AdminFormElement::dependentselect( 'profile.city', 'From City' )
                    ->setModelForOptions( Cities::class, 'name' )
                    ->required(),
            ], 'col-xs-12 col-sm-6 col-md-2 col-lg-2' )->addColumn( [
                AdminFormElement::text('phone', 'Phone')->required()
            ], 'col-xs-12 col-sm-6 col-md-2 col-lg-2' )->addColumn( [
                AdminFormElement::text('email', 'Email')->required()
            ], 'col-xs-12 col-sm-6 col-md-2 col-lg-2' )->addColumn([
                AdminFormElement::radio( 'status', 'Status' )
                    ->setEnum([
                        'new'   => 'new',
                        'checking' => 'checking',
                        'confirmed' => 'confirmed',
                    ] )
                    ->required()
            ])->addColumn([
                AdminFormElement::custom()->setDisplay(function(Model $model) {
                    return "<div class='photo__driver'><div class='photos__item'><img src='" . $model->profile->photo . "'></div></div>";
                })
            ], 'col-xs-12 col-sm-6 col-md-6 col-lg-6' )->addColumn( [
                AdminFormElement::html('<div><b>Company</b><hr></div>')
            ], 'col-xs-12 col-sm-12 col-md-12 col-lg-12' )->addColumn( [
                AdminFormElement::text('company.name', 'Name of company')->required()
            ], 'col-xs-12 col-sm-6 col-md-2 col-lg-2' )->addColumn( [
                AdminFormElement::text('company.email', 'Email')
            ], 'col-xs-12 col-sm-6 col-md-2 col-lg-2' )->addColumn( [
                AdminFormElement::text('company.represent_first_name', 'First name')
            ], 'col-xs-12 col-sm-6 col-md-2 col-lg-2' )->addColumn( [
                AdminFormElement::text('company.represent_last_name', 'Last name')
            ], 'col-xs-12 col-sm-6 col-md-2 col-lg-2' )->addColumn( [
                AdminFormElement::text('company.represent_date_of', 'Date of')
            ], 'col-xs-12 col-sm-6 col-md-2 col-lg-2' )->addColumn( [
                AdminFormElement::text('company.represent_country', 'Legal representative info Country')
            ], 'col-xs-12 col-sm-6 col-md-2 col-lg-2' )->addColumn( [
                AdminFormElement::text('company.billing_country', 'Bank Account Country')
            ], 'col-xs-12 col-sm-6 col-md-2 col-lg-2' )->addColumn( [
                AdminFormElement::text('company.billing_company', 'Company ID number')
            ], 'col-xs-12 col-sm-6 col-md-2 col-lg-2' )->addColumn( [
                AdminFormElement::text('company.address_country', 'Legal representative address Country')
            ], 'col-xs-12 col-sm-6 col-md-2 col-lg-2' )->addColumn( [
                AdminFormElement::text('company.address_city', 'Legal representative address City')
            ], 'col-xs-12 col-sm-6 col-md-2 col-lg-2' )->addColumn( [
                AdminFormElement::text('company.address_address', 'Legal representative address')
            ], 'col-xs-12 col-sm-6 col-md-2 col-lg-2' )->addColumn( [
                AdminFormElement::text('company.address_postal_code', 'Postal code')
            ], 'col-xs-12 col-sm-6 col-md-2 col-lg-2' )->addColumn( [
                AdminFormElement::text('company.vat', 'Vat')
            ], 'col-xs-12 col-sm-6 col-md-2 col-lg-2' )->addColumn( [
                AdminFormElement::text('company.iban', 'Iban')
            ], 'col-xs-12 col-sm-6 col-md-2 col-lg-2' )->addColumn( [
                AdminFormElement::text('company.head_country', 'Headquarters Country')
            ], 'col-xs-12 col-sm-6 col-md-2 col-lg-2' )->addColumn( [
                AdminFormElement::text('company.head_city', 'Headquarters City')
            ], 'col-xs-12 col-sm-6 col-md-2 col-lg-2' )->addColumn( [
                AdminFormElement::text('company.head_address', 'Headquarters Address')
            ], 'col-xs-12 col-sm-6 col-md-2 col-lg-2' )->addColumn( [
                AdminFormElement::text('company.head_postal_code', 'Headquarters postal code')
            ], 'col-xs-12 col-sm-6 col-md-2 col-lg-2' ),
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
