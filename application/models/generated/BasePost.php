<?php

/**
 * BasePost
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $title
 * @property string $body
 * @property timestamp $date
 * @property integer $author
 * @property integer $blog_id
 * @property User $User
 * @property Blog $Blog
 * @property Doctrine_Collection $Comments
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 6820 2009-11-30 17:27:49Z jwage $
 */
abstract class BasePost extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('post');
        $this->hasColumn('id', 'integer', 4, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             'length' => '4',
             ));
        $this->hasColumn('title', 'string', 255, array(
             'type' => 'string',
             'length' => '255',
             ));
        $this->hasColumn('body', 'string', null, array(
             'type' => 'string',
             ));
        $this->hasColumn('date', 'timestamp', null, array(
             'type' => 'timestamp',
             ));
        $this->hasColumn('author', 'integer', 4, array(
             'type' => 'integer',
             'length' => '4',
             ));
        $this->hasColumn('blog_id', 'integer', 4, array(
             'type' => 'integer',
             'length' => '4',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('User', array(
             'local' => 'author',
             'foreign' => 'id'));

        $this->hasOne('Blog', array(
             'local' => 'blog_id',
             'foreign' => 'id'));

        $this->hasMany('Comments', array(
             'local' => 'id',
             'foreign' => 'post_id'));
    }
}