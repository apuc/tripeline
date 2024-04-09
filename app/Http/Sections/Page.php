<?php

namespace App\Http\Sections;

use AdminColumn;
use AdminColumnFilter;
use AdminDisplay;
use AdminForm;
use AdminFormElement;
use AdminDisplayFilter;
use Illuminate\Database\Eloquent\Model;
use SleepingOwl\Admin\Contracts\Display\DisplayInterface;
use SleepingOwl\Admin\Contracts\Form\FormInterface;
use SleepingOwl\Admin\Contracts\Initializable;
use SleepingOwl\Admin\Form\Buttons\Cancel;
use SleepingOwl\Admin\Form\Buttons\Save;
use SleepingOwl\Admin\Form\Buttons\SaveAndClose;
use SleepingOwl\Admin\Form\Buttons\SaveAndCreate;
use SleepingOwl\Admin\Section;
use Illuminate\Support\Facades\Auth;
use Spatie\LaravelImageOptimizer\Facades\ImageOptimizer;


/**
 * Class Page
 *
 * @property \App\Models\Page $model
 *
 * @see https://sleepingowladmin.ru/#/ru/model_configuration_section
 */
class Page extends Section implements Initializable
{
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
    public function initialize()
    {
        $this->addToNavigation()->setPriority(101)->setIcon('fas fa-newspaper');
    }

    /**
     * @param array $payload
     *
     * @return DisplayInterface
     */
    public function onDisplay($payload = [])
    {
        $columns = [
            AdminColumn::text('id', '#')->setWidth('50px')->setHtmlAttribute('class', 'text-center'),
            AdminColumn::link('title_en', 'Title', 'slug')
                ->setSearchCallback(function($column, $query, $search){
                    return $query
                        ->orWhere('title_en', 'like', '%'.$search.'%')
                        ->orWhere('body_en', 'like', '%'.$search.'%');
                }),
            AdminColumn::text('lang', 'Language', 'lang')->setSearchable( false ),
            AdminColumn::text('created_at', 'Created / updated', 'updated_at')
                ->setWidth('160px')
                ->setOrderable(function($query, $direction) {
                    $query->orderBy('updated_at', $direction);
                })
                ->setSearchable(false)
            ,
        ];

        app()->getLocale();

        $display = AdminDisplay::datatables()
            ->setName('firstdatatables')
            ->setOrder([[0, 'asc']])
            ->setDisplaySearch(true)
            ->paginate(25)
            ->setColumns($columns)
            ->setHtmlAttribute('class', 'table-primary table-hover th-center');

        $display->getColumnFilters()->setPlacement('card.heading');

        return $display;
    }

    /**
     * @param int|null $id
     * @param array $payload
     *
     * @return FormInterface
     */
    public function onEdit($id = null, $payload = [])
    {
        $tabs = AdminDisplay::tabbed();
        $form = AdminForm::card()->addBody(
            [
                AdminFormElement::columns()->addColumn(
                    [
                        AdminFormElement::hidden( 'user_id' )->setDefaultValue(Auth::id()),
                        AdminFormElement::text('slug', 'Slug')
                                        ->required()
                        ->setReadonly($id !== null),
                        AdminFormElement::html('<hr>')
                    ], 'col-xs-12 col-sm-12 col-md-12 col-lg-12')->
                addColumn([
                    AdminFormElement::html('<b>Ex: https://www.youtube.com/watch?v=NpEaa2P7qZI</b><br>params: ?autoplay=1&mute=1 for autoplay'),
                    AdminFormElement::textarea('embed_video', 'Video url:', 'ckeditor'),
                ], 'col-xs-12 col-sm-12 col-md-12 col-lg-12')->
                addColumn( [
                    AdminFormElement::image( 'block_image', 'Image' )
                        ->setAfterSaveCallback(function ($value, $model) {
                            if ($value) {
                                $map = collect($value)->map(function ($item) {
                                    ImageOptimizer::optimize($item);
                                });
                            }
                        })
                    ,
                ], 'col-xs-12 col-sm-6 col-md-8 col-lg-8' )->
                addColumn( [
                    AdminFormElement::text( 'video_block_title', 'Video block title' ),
                ], 'col-xs-12 col-sm-6 col-md-8 col-lg-8' ),
            ]
        );


        $tabs->setTabs(function ($id) {
            $tabs = [];
            $tabs[] = AdminDisplay::tab(
                AdminFormElement::columns()->addColumn(
                    [
                        AdminFormElement::text('title_en', 'Title')
                                        ->required(),
                        AdminFormElement::html('<hr>')
                    ], 'col-xs-12 col-sm-6 col-md-6 col-lg-6')->
                addColumn([
                    AdminFormElement::text('meta_title_en', 'Meta title')
                                    ->required(),
                    AdminFormElement::text('meta_keywords_en', 'Meta keywords')
                                    ->required(),
                    AdminFormElement::textarea('meta_descriptions_en', 'Meta descriptions')
                                    ->required(),
                ], 'col-xs-12 col-sm-6 col-md-6 col-lg-6')->
                addColumn([
                    AdminFormElement::wysiwyg('body_en', 'Content', 'ckeditor')
                                    ->required(),
                ], 'col-xs-12 col-sm-12 col-md-12 col-lg-12'),
            )->setLabel('EN');

            $tabs[] = AdminDisplay::tab(
                AdminFormElement::columns()->addColumn(
                    [
                        AdminFormElement::text('title_de', 'Title'),
                        AdminFormElement::html('<hr>'),
                    ], 'col-xs-12 col-sm-6 col-md-6 col-lg-6')->
                addColumn([
                    AdminFormElement::text('meta_title_de', 'Meta title'),
                    AdminFormElement::text('meta_keywords_de', 'Meta keywords'),
                    AdminFormElement::textarea('meta_descriptions_de', 'Meta descriptions'),
                ], 'col-xs-12 col-sm-6 col-md-6 col-lg-6')->
                addColumn([
                    AdminFormElement::wysiwyg('body_de', 'Content', 'ckeditor'),
                ], 'col-xs-12 col-sm-12 col-md-12 col-lg-12'),
            )->setLabel('DE');

            $tabs[] = AdminDisplay::tab(
                AdminFormElement::columns()->addColumn(
                    [
                        AdminFormElement::text('title_ch', 'Title'),
                        AdminFormElement::html('<hr>'),
                    ], 'col-xs-12 col-sm-6 col-md-6 col-lg-6')->
                addColumn([
                    AdminFormElement::text('meta_title_ch', 'Meta title'),
                    AdminFormElement::text('meta_keywords_ch', 'Meta keywords'),
                    AdminFormElement::textarea('meta_descriptions_ch', 'Meta descriptions'),
                ], 'col-xs-12 col-sm-6 col-md-6 col-lg-6')->
                addColumn([
                    AdminFormElement::wysiwyg('body_ch', 'Content', 'ckeditor'),
                ], 'col-xs-12 col-sm-12 col-md-12 col-lg-12'),
            )->setLabel('CH');

            $tabs[] = AdminDisplay::tab(
                AdminFormElement::columns()->addColumn(
                    [
                        AdminFormElement::text('title_ru', 'Title'),
                        AdminFormElement::html('<hr>'),
                    ], 'col-xs-12 col-sm-6 col-md-6 col-lg-6')->
                addColumn([
                    AdminFormElement::text('meta_title_ru', 'Meta title'),
                    AdminFormElement::text('meta_keywords_ru', 'Meta keywords'),
                    AdminFormElement::textarea('meta_descriptions_ru', 'Meta descriptions'),
                ], 'col-xs-12 col-sm-6 col-md-6 col-lg-6')->
                addColumn([
                    AdminFormElement::wysiwyg('body_ru', 'Content', 'ckeditor'),
                ], 'col-xs-12 col-sm-12 col-md-12 col-lg-12'),
            )->setLabel('RU');

            $tabs[] = AdminDisplay::tab(
                AdminFormElement::columns()->addColumn(
                    [
                        AdminFormElement::text('title_es', 'Title'),
                        AdminFormElement::html('<hr>'),
                    ], 'col-xs-12 col-sm-6 col-md-6 col-lg-6')->
                addColumn([
                    AdminFormElement::text('meta_title_es', 'Meta title'),
                    AdminFormElement::text('meta_keywords_es', 'Meta keywords'),
                    AdminFormElement::textarea('meta_descriptions_es', 'Meta descriptions'),
                ], 'col-xs-12 col-sm-6 col-md-6 col-lg-6')->
                addColumn([
                    AdminFormElement::wysiwyg('body_es', 'Content', 'ckeditor'),
                ], 'col-xs-12 col-sm-12 col-md-12 col-lg-12'),
            )->setLabel('ES');

            return $tabs;
        });
        $form->addHeader([
            $tabs
        ]);

        $form->getButtons()->setButtons([
            'save'  => new Save(),
            'save_and_close'  => new SaveAndClose(),
            'save_and_create'  => new SaveAndCreate(),
            'cancel'  => (new Cancel()),
        ]);

        return $form;
    }

    /**
     * @return FormInterface
     */
    public function onCreate($payload = [])
    {
        return $this->onEdit(null, $payload);
    }

    /**
     * @return bool
     */
    public function isDeletable(Model $model)
    {
        return false;
    }

    /**
     * @return void
     */
    public function onRestore($id)
    {
        // remove if unused
    }
}
