<?php
namespace App\Model\Entity;
use Cake\ORM\Entity;
/**
 * User Entity.
 */
class User extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array
     */
    protected $_accessible = [
        'username' => true,
    ];
  
   /**
    *  hiden fields
    * */
   protected $_hidden = ['password'];
}