<?php

namespace App\Http\Sections;

use AdminColumn;
use AdminColumnFilter;
use AdminDisplay;
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

/**
 * Class Users
 *
 * @property \App\Models\User $model
 *
 * @see https://sleepingowladmin.ru/#/ru/model_configuration_section
 */
class Users extends Section implements Initializable {
    /**
     * @var bool
     */
    protected $checkAccess = false;

    /**
     * @var string
     */
    protected $title = "Users";

    /**
     * @var string
     */
    protected $alias = "users";

    /**
     * Initialize class.
     */
    public function initialize() {
        $this->addToNavigation()->setPriority( 150 )->setIcon( 'fas fa-users' );
    }

    /**
     * @param array $payload
     *
     * @return DisplayInterface
     */
    public function onDisplay( $payload = [] ) {
        $columns = [
            AdminColumn::text( 'id', '#' )->setWidth( '50px' )->setHtmlAttribute( 'class', 'text-center' ),
            AdminColumn::link( 'email', 'Email', 'created_at' )
                       ->setSearchCallback( function ( $column, $query, $search ) {
                           return $query
                               ->orWhere( 'email', 'like', '%' . $search . '%' )
                               ->orWhere( 'created_at', 'like', '%' . $search . '%' );
                       } )
                       ->setOrderable( function ( $query, $direction ) {
                           $query->orderBy( 'created_at', $direction );
                       } )
            ,
            AdminColumn::text( 'first_name', 'First name' ),
            AdminColumn::text( 'last_name', 'Last name' ),
            AdminColumn::text( 'created_at', 'Created / updated', 'updated_at' )
                       ->setWidth( '160px' )
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

        $display->setApply(function ($query)
        {
            $query->where('role_id', '<', 3);
            $query->orderBy('created_at', 'desc');
        });

        //dd(\App\Models\User::all());

        $display->setColumnFilters( [
            AdminColumnFilter::select(\App\Models\User::class, 'email')->setDisplay('email')
                ->setPlaceholder('All email')
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
                AdminFormElement::text( 'first_name', 'First name' )
                                ->required()
                ,
                AdminFormElement::text( 'last_name', 'Last name' )
                                ->required(),
                AdminFormElement::select( 'role_id', 'Role' )
                                ->setModelForOptions( \App\Models\Role::class, 'name' )
                                ->required(),
                AdminFormElement::html( '<hr>' ),
            ], 'col-xs-12 col-sm-6 col-md-4 col-lg-4' )->addColumn( [
                AdminFormElement::text( 'email', 'Email' )
                                ->required()
                ,
                AdminFormElement::password( 'password', 'Password' )
                                ->required()
                                ->hashWithBcrypt()
                ,
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
    public function isCreatable() {
        return Auth::user()->isAdmin();
    }

    /**
     * @return bool
     */
    public function isEditable( Model $model ) {
        return Auth::user()->isAdmin();
    }

    /**
     * @return bool
     */
    public function isDeletable( Model $model ) {
        return Auth::user()->isAdmin();
    }

    /**
     * @return void
     */
    public function onRestore( $id ) {
        // remove if unused
    }
}
