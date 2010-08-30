<?php
/**
 * Serializable Model Behavior
 * 
 * Serializes model data
 *
 * @package serializable
 * @author Jose Diaz-Gonzalez
 * @version 1.0
 * @license MIT License
 **/
class SerializableBehavior extends ModelBehavior {

/**
 * Contains configuration settings for use with individual model objects.
 * Individual model settings should be stored as an associative array, 
 * keyed off of the model name.
 *
 * @var array
 * @access public
 * @see Model::$alias
 */
    var $settings = array();

/**
 * Initiate Serializable Behavior
 *
 * @param object $model
 * @param array $config
 * @return void
 * @access public
 */
    function setup(&$model, $config = array()) {
        $this->settings[$model->alias] = $config;
    }

/**
 * After find callback. Can be used to modify any results returned by find and findAll.
 *
 * @param object $model Model using this behavior
 * @param mixed $results The results of the find operation
 * @param boolean $primary Whether this model is being queried directly (vs. being queried as an association)
 * @return mixed Result of the find operation
 * @access public
 */
    function afterFind(&$model, $results, $primary) {
        foreach ($this->settings[$model->alias] as $field) {
            $results[$model->alias][$field] = unserialize($results[$model->alias][$field]);
        }
        return $results;
    }

/**
 * Before save callback
 *
 * @param object $model Model using this behavior
 * @return boolean True if the operation should continue, false if it should abort
 * @access public
 */
    function beforeSave(&$model) {
        foreach ($this->settings[$model->alias] as $field) {
            $model->data[$model->alias][$field] = serialize($model->data[$model->alias][$field]);
        }
        return true;
    }

}
