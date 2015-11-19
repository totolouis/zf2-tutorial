<?php

namespace User\Model;

use Zend\Db\TableGateway\TableGateway;

class UserTable {
	
	protected $tableGateway;
	
	public function __construct(TableGateway $tableGateway) {
		$this->tableGateway = $tableGateway;
	}
	
	public function getUser($id)
	{
		$id  = (int) $id;
		$rowset = $this->tableGateway->select(array(
				'id' => $id
		));
		$row = $rowset->current();
		if (!$row) {
			throw new \Exception("Could not find row $id");
		}
		return $row;
	}
	
	public function getUserByName($username){
	    $rowset = $this->tableGateway->select(array(
	    		'username' => $username
	    ));
	    $row = $rowset->current();
	    
	    return $row;
	}
	
	public function createAccount(User $user)
	{
		$data = array(
		    'username' => $user->username,
		    'password'  => sha1($user->password)
		);
		
		$this->tableGateway->insert($data);
	}
	
	public function modifyAccount(User $user){
		$data = array(
				'username' => $user->username
		);
		 
		$this->tableGateway->update($data, array('id' => $user->id));
		 
	}
	public function removeAccount($id){
		$id  = (int)$id;
		$resultSet = $this->tableGateway->delete(array(
		    'id' => $id
		));
		return $resultSet;
	}
}