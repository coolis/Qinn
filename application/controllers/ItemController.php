<?php
class ItemController extends Controller {
	function view($id = null, $name = null) {
		$this->set('title',$name.'My Todo List App');
		$this->set('todo',$this->Item->find($id));
	}
}