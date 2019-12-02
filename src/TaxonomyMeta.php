<?php

namespace AndreKeher\WPDP;

class TaxonomyMeta
{
    /**
     * @var string|array $taxonomies
     */
    private $taxonomies;

    /**
     * @var callable $addFormFunction
     */
    private $addFormFunction;

    /**
     * @var callable $editFormFunction
     */
    private $editFormFunction;

    /**
     * @var callable $saveFunction
     */
    private $saveFunction;

    /**
     * TaxonomyMetabox::__construct()
     *
     * @param string|array $taxonomies
     *
     * @return void
     */
    public function __construct($taxonomies)
    {
        $this->setTaxonomies($taxonomies);
    }

    /**
     * TaxonomyMetabox::init()
     *
     * @return void
     */
    public function init()
    {
        if (empty($this->taxonomies)) {
            return false;
        }
        foreach ($this->taxonomies as $tax) {
            add_action($tax . '_add_form_fields', $this->addFormFunction, 10, 2);
            add_action($tax . '_edit_form_fields', $this->editFormFunction, 30);

            add_action('created_' . $tax, [$this, 'save'], 10, 2);
            add_action('edited_' . $tax, [$this, 'save'], 10, 2);
        }
    }

    /**
     * TaxonomyMetabox::setTaxonomies()
     *
     * @param string|array $taxonomies
     *
     * @return void
     */
    public function setTaxonomies($taxonomies)
    {
        $this->taxonomies = (array) $taxonomies;
    }

    /**
     * TaxonomyMetabox::setAddFormFunction()
     *
     * @param callable $addFormFunction
     *
     * @return void
     */
    public function setAddFormFunction(callable $addFormFunction)
    {
        $this->addFormFunction = $addFormFunction;
    }

    /**
     * TaxonomyMetabox::setEditFormFunction()
     *
     * @param callable $editFormFunction
     *
     * @return void
     */
    public function setEditFormFunction(callable $editFormFunction)
    {
        $this->editFormFunction = $editFormFunction;
    }

    /**
     * TaxonomyMetabox::setSaveFunction()
     *
     * @param callable $saveFunction
     *
     * @return void
     */
    public function setSaveFunction(callable $saveFunction)
    {
        $this->saveFunction = $saveFunction;
    }

    /**
     * TaxonomyMetabox::save()
     *
     * @param int $term
     * @param int $ttId
     *
     * @return void
     */
    public function save($term, $ttId = null)
    {
        extract($_POST);
        if (empty($term) || ! isset($taxonomy) || ! in_array($taxonomy, $this->taxonomies)) {
            return false;
        }
        call_user_func($this->saveFunction, $term, $ttId);
    }
}
