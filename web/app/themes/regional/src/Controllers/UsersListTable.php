<?php

namespace App\Controllers;

class UsersListTable extends \WP_List_Table {

    private $data;
    private $model;
    private $sortable;

    /** Class constructor */
    public function __construct(array $data = array(), array $model = array(), $sortable = array())
    {
        $this->data = $data;
        $this->model = $model;
        $this->sortable = $sortable;

        parent::__construct([
            'singular' => __('New User', 'extranet'), //singular name of the listed records
            'plural' => __('New Users', 'extranet'), //plural name of the listed records
            'ajax' => false //should this table support ajax?

        ]);
    }

    public function get_columns() {
        $table_columns = $this->model;
        return $table_columns;
    }
    public function prepare_items($data = array()) {

        $columns = $this->get_columns();
        $hidden = array();
        $totalItems = count($this->data);
        usort( $this->data, array( &$this, 'sort_data' ) );
        $perPage = 10;
        $currentPage = $this->get_pagenum();

        $this->set_pagination_args( array(
            'total_items' => $totalItems,
            'per_page'    => $perPage
        ) );

        $data = array_slice($this->data,(($currentPage-1)*$perPage),$perPage);
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->items = $data;
    }

    protected function column_default( $item, $column_name ) {
        /*switch ( $column_name ) {
            case 'post_id':
            case 'post_title':
                return $item[ $column_name ];
            case 'post_category':
                return $item[ $column_name ];
            case 'post_convert':
                return $item[ $column_name ];
            default:
                return print_r( $item, true ); // Show the whole array for troubleshooting purposes.
        }*/
        return $item[ $column_name ];
    }


    /**
     * Get value for checkbox column.
     *
     * REQUIRED if displaying checkboxes or using bulk actions! The 'cb' column
     * is given special treatment when columns are processed. It ALWAYS needs to
     * have it's own method.
     *
     * @param object $item A singular item (one full row's worth of data).
     * @return string Text to be placed inside the column <td>.

    protected function column_cb( $item ) {
    return sprintf(
    '<input type="checkbox" name="%1$s[]" value="%2$s" />',
    $this->_args['singular'],  // Let's simply repurpose the table's singular label ("movie").
    $item['ID']                // The value of the checkbox should be the record's ID.
    );
    }*/

    public function no_items() {
        _e( 'No data avaliable.', 'extranet' );
    }

    function get_sortable_columns() {
        return $this->sortable;
    }

    /* function get_bulk_actions()
    {
        $actions = array(
            'convert_to_bip'    => 'Convertir en Bip',
            'delete'    => 'Supprimer de la liste',
        );

        return $actions;
    }

    function process_bulk_action()
    {
        global $wpdb;

        if ('delete' === $this->current_action()) {
            // actions to delete from list
        }

        if ('convert_to_bip' === $this->current_action()) {
            // actions to convert to bip
        }
    } */

    function custom_bulk_admin_notices()
    {
        echo 'Hello.';
    }
}