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
 * @property integer $status
 * @property boolean $comments
 * @property integer $blog_id
 * @property User $User
 * @property Blog $Blog
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
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
        $this->hasColumn('status', 'integer', 1, array(
             'type' => 'integer',
             'length' => '1',
             ));
        $this->hasColumn('comments', 'boolean', null, array(
             'type' => 'boolean',
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
    }
}