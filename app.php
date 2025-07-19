<?
class table_structure_update {
	public function __construct($init_command, $data_instance) {
		$create_commands = $object->strings->split($init_command, 'CREATE TABLE IF NOT EXISTS');
		foreach($create_commands as $item) {
			$table_parts = $object->strings->split($item, '(');
			if($table_parts->length > 1) {
				$nametrimmed = $object->strings->trim($table_parts[0]);
				$sub_items = $object->strings->split($table_parts[1], ')')[0];
				$sub_items = $object->strings->split($sub_items, ',');
				$index = 0;
				$columns = $data_instance->table_columns($nametrimmed);
				foreach($sub_items as $sub_item) {
					$sub_item_value = $object->strings->split($object->strings->trim($sub_item), ' ')[0];
					$flag = false;
					foreach($columns as $column) {
						if($column == $sub_item_value) {
							$flag = true;
						}
					}
					if(!$flag) {
						$data_instance->execute('ALTER TABLE '.$nametrimmed.' ADD COLUMN '.$sub_item, []);
					} else {
					}
				}
			}
		}
	}
}

class statement_depr {
	public function generate($x, $table) {
		$output = NULL;
		$keys = [];
		$type = 0;
		if($x->length > 0) {
			if($object->isset($x['id'])) {
				if($x['id'] == '-1') {
					$type = 1;	
				} else {
					$type = 0;	
				}
			} else {
				$type = 1;	
			}
			if($type == 0) {
				$output = 'UPDATE '.$table.' SET ';
				if($object->isset($x['id'])) {
					$counter = 0;
					foreach($x as $key => $x_value) {
						$keys[] = $key;
						if($key != 'id') {
							if($counter > 0) {
								$output = $output.', ';
							}
							$output = $output.$key.' = ?';
							$counter = $counter+1;
						}
					}
					$output = $output.' WHERE id = ?';
				}
			} else {
				$output = 'INSERT INTO '.$table.' (';
				$counter = 0;
				foreach($x as $key => $x_value) {
					$keys[] = $key;
					if($counter > 0) {
						$output = $output.', ';
					}
					$output = $output.$key;
					$counter = $counter+1;
				}
				$counter = 0;
				$output = $output.') VALUES (';
				foreach($x as $key => $x_value) {
					if($counter > 0) {
						$output = $output.', ';
					}
					$output = $output.'? ';
					$counter = $counter+1;
				}
				$output = $output.')';
			}
			$result_a = ['insert_query' => $output, 'table_name' => $table, 'type' => $type];
			return $output;
		}
		return NULL;
	}
}

class statement {
	public function generate($x, $table, $prevent_id_generating=false) {
		$output = NULL;
		$keys = [];
		$type = 0;
		if($object->isset($x['modified']) && $x['modified'] == (-1)) {
			delete $x['modified'];
		}
		if($x->length > 0) {
			if($object->isset($x['id'])) {
				if($x['id'] == '-1') {
					$type = 1;	
				} else {
					$type = 0;	
				}
			} else {
				$type = 1;	
			}
			if($type == 0) {
				$output = 'UPDATE '.$table.' SET ';
				if($object->isset($x['id'])) {
					$counter = 0;
					if($object->isset($x['created'])) {
						delete $x['created'];
					}
					foreach($x as $key => $x_value) {
						$keys[] = $key;
						if($key != 'id') {
							if($counter > 0) {
								$output = $output.', ';
							}
							if(($key == 'created' || $key == 'modified') && $object->strings->lower($x_value) == 'current_timestamp') {
								if($key != 'created') {
									$output = $output.$key.' = current_timestamp';
								}
							} else {
								$output = $output.$key.' = ?';
							}
							$counter = $counter+1;
						}
					}
					$output = $output.' WHERE id = ?';
				}
			} else {
				$output = 'INSERT INTO '.$table.' (';
				$counter = 0;
				foreach($x as $key => $x_value) {
					$keys[] = $key;
					if($counter > 0) {
						$output = $output.', ';
					}
					$output = $output.$key;
					$counter = $counter+1;
				}
				$counter = 0;
				$output = $output.') VALUES (';
				foreach($x as $key => $x_value) {
					if($counter > 0) {
						$output = $output.', ';
					}
					if(($key == 'created' || $key == 'modified') && $object->strings->lower($x_value) == 'current_timestamp') {
						$output = $output.'current_timestamp ';
					} else {
						$output = $output.'? ';
					}
					$counter = $counter+1;
				}
				$output = $output.')';
			}

			if($object->isset($x['modified']) && $x['modified'] == 'current_timestamp') {
				delete $x['modified'];
			}
			if($object->isset($x['created']) && $x['created'] == 'current_timestamp') {
				delete $x['created'];
			}
			
			return $output;
		}
		return NULL;
	}
}


class base {
	public $apps=NULL;
	private $dict = NULL;

	public function testa($value) {
		return $value+1;
	}

	public function __construct() {
		$object->log('begin construct');
		$object->log('begin construct1');
		$data->statement = new statement();
		$object->log('begin construct1');
		$object->deep_copy = function($input) {
			return $object->fromJSON($object->toJSON($input));
		};
		$object->log('begin construct2');
		$object->index_of = function($input, $search_value) {
			foreach($input as $indexofkey => $value) {
				if($search_value == $value) {
					return $indexofkey;
				}
			}
			return 0-1;
		};
		$object->log('main __construct0-0');
		$object->union = function($a, $b, $comparator) {
			foreach($b as $b_value) {
				$exists = false;
				foreach($a as $a_value) {
					$comparator_result = $comparator($a_value, $b_value);
					if($comparator_result) {
						$exists = true;
					}
				}
				if(!$exists) {
					$a[] = $b_value;
				}
			}
			return NULL;
		};
		$object->log('main __construct0-1');
		$object->map = function($arr, $func) {
			$result = [];
			foreach($arr as $row) {
				$result[] = $func($row);
			}
			return $result;
		};
		$object->log('main __construct0-2');
    	$files->append_path = function($path, $file) {
    		$path_strlen = $object->strings->strlen($path);
    		$strrev = $object->strings->strrev($path);
    		if($object->strings->strpos($strrev, '/') == 0) {
    			$path = $object->strings->substr($path, 0, $path_strlen-1);
    		}
    		if($object->strings->strpos($file, '/') != 0) {
    			$file = '/'.$file;
    		}
    		return $path.$file;
    	};
		$object->log('main __construct0-3');
    	$object->create = function($input) {
			$created_object = ['test' => 'test'];
			delete $created_object['test'];
			return $created_object;
		};
		$object->log('main __construct0-4');

		$object->log($object->strings);
		$object->log('after log strings');
		/*$test = [
			'test_sub' => $object->create()
		];
		$test['test_sub']['test_value'] = 1;
		$object->log($test['test_sub']);
		$object->log($test['test_sub']['test_value']);
		$object->log('after log strings');
		$object->strings->test = 1;
		$object->log($object->strings->test);*/
		/*$test = [1, 2, 3];
		$object->log($test);
		$object->log($test[0]);*/


		/*$res = $this->testa(2);

		$object->log('res');
		$object->log($res);*/

		/*$object->log($object->toJSON($test));
		$object->log('after log strings');*/

		/*$object->strings->implode = function($delimiter, $values) {
			return $object->strings->join($values, $delimiter);
		};

		$object->log('main __construct0-5');
		$object->strings->explode = function($delimiter, $values) {
			return $object->strings->split($values, $delimiter);
		};*/
		$object->log('main __construct0-6');

		$object->count = function($input) {
			return $input->length;
		};
		$object->log('main __construct0-7');

		$object->in_array = function($item, $arr) {
			if($object->index_of($arr, $item) == (-1)) {
				return false;
			}
			return true;
		};
		$object->log('main __construct0-8');

		$object->array_reverse = function($arr) {
			return $object->reverse($arr);
		};

		$object->log('main __construct0');
		/*$this->apps = [
			'main' => new main_app($this)
    	];*/
		$object->log('main __construct1');

		
	}

	public $ws_connections = [];

	public function __add_ws_connection($ws_item) {
		$this->ws_connections[] = $ws_item;

		$self = $this;

		$ws_item->add_remove_callback(function() {
			$object->remove_item($self->ws_connections, $ws_item);
		});

		/*$response_callback = function($message) {
			$set_message_counter = (-1);
			if($object->isset($message['message_counter'])) {
				$set_message_counter = $message['message_counter'];
			}
			$object->log($object->toJSON($message));
			$object->log('set message counter');
			$object->log($set_message_counter);
				$callback = function($response) {
					$response['message_counter'] = $set_message_counter;
					$ws_item->send($response);
				};
				
				$self->__receive_request(['message' => $message, 'callback' => $callback]);
		};*/

		/*$ws_item->add_response_callback(function($message) {
			$response_callback($message);
		});*/

		$ws_item->add_response_callback(function($message) {
			/*$set_message_counter = (-1);
			$object->log($object->toJSON($message));
			$object->log('set message counter');
			$object->log($set_message_counter);*/
			$callback = function($response) {
				/*$response['message_counter'] = $set_message_counter;*/

				if($object->isset($message['message_counter'])) {
					$response['message_counter'] = $message['message_counter'];
				}

				$ws_item->send($response);
			};
			
			$self->__receive_request(['message' => $message, 'callback' => $callback]);
	
		});
	}

	public $evaluation = NULL;

	public $indexing_in_progress = false;

	public $running_functions = [];

	public function __receive_request($input) {
    	$message = $input['message'];
    	$object->log($object->toJSON(['req' => $message]));
    	$action = $message['action'];
    	$inputs = $object->fromJSON($message['inputs']);
    	$callback = $input['callback'];


    	if(!$object->isset($message['action'])) {
    		$callback(['response' => 0]);
    	} else {

	    	if($object->isset($message['callback_result'])) {
	    		$this->action_handler->handle_action($message, function($results) {
    				$return_result = [
						'has_access' => true,
						'result' => $results
					];
	    			$callback($return_result);
	    		});
	    	} else {
	    		$wrap = function() {
					$evaluation = new evaluation();

	 				$object->call_func($evaluation['__set_configuration'], $inputs['config']);


					$result_data = $object->call_func($evaluation[$action], $inputs['inputs']);
					$result_data = ['result_data' => $result_data];

		    		$callback($result_data);
		    	};
		    	$wrap();
	    	}
	    }
	    /*return NULL;*/
    }

    public function __receive_messages($message) {
		$object->log($object->toJSON($message));
    	$message_counter = $message['message_counter'];
    	delete $message['message_counter'];

    	$action = $message['action'];
    	delete $message['action'];
    	$result = NULL;
		/*if($this->indexing_in_progress) {
			return [
	        	'message_counter' => $message_counter,
	        	'message' => $result,
	        	'stall' => true
	        ];
		}*/

		$module = NULL;
		if($object->isset($message['module'])) {
			$module = $message['module'];
			/*if($module == 'test') {
				$wrap = function() {
					$object->log('in wrap');
					$object->log($object->toJSON($message['data']));
					
					$permutations = new permutations($message['data']);

					$permutations->generate(NULL, function($result_data) {
						$object->log('end result');
						$object->log($object->toJSON($result_data));

						$object->send('app.receive_messages(data)', [
		    				'data' => [
			    				'message_counter' => $message_counter, 
			    				'message' => $result_data
			    			]
		    			]);
					});

				};
				$wrap();
				return NULL;
			} else */
			if($module == 'server') {
				$object->log('start server');
				$object->log($object->start_server);
				$object->start_server($message['ip'], $message['port']);
				$object->send('app.receive_messages(data)', [
    				'data' => [
	    				'message_counter' => $message_counter, 
	    				'message' => ['result' => true]
	    			]
    			]);
				return NULL;
			} else if($module == 'numeval') {
				$self = $this;
				/*$evaluation = $this->evaluation;*/
				$object->log('in1');
				/*$wrap = function() {*/ /*async*/
					$result_data = ['res' => 0];
					$evaluation = new evaluation();
					$object->log('in2');
		    			/*if($action == 'quick_numeric') {
		    			$result_data = $evaluation->quick_numeric(['value' => '1', 'remainder' => '1/3'], 50);
		    			$object->send('app.receive_messages(data)', [
		    				'data' => [
			    				'message_counter' => $message_counter, 
			    				'message' => $result_data
			    			]
		    			]);

					} else {*/
						/*$result_data = $object->call_func($evaluation[$action], $message['values']);
					$object->log('in3');*/
					if($action == 'numeric_whole') {
						$result_data = $evaluation->numeric_whole($message['values'][0], $message['values'][1], $message['values'][2]);
					} else {
						$result_data = $evaluation->execute_divide($message['values'][0], $message['values'][1]);
					}
						$object->log($object->toJSON(['result_data' => $result_data]));
						$object->send('app.receive_messages(data)', [
		    				'data' => [
			    				'message_counter' => $message_counter, 
			    				'message' => $result_data
			    			]
		    			]);
					/*}*/
	    			/*$result_data = [*/


			        /*return [
			        	'message_counter' => $message_counter,
			        	'message' => $result
			        ];*/
			   	/*};*/

			    /*$call_outer = async function() {
				    $wrap($action, $message);
				};

				$call_outer();*/

				/*$wrap();*/
			    return NULL;
			}
		}

		$object->log($object->toJSON($message));
		
    	if($object->isset($message['callback_result'])) {
    		delete $message['callback_result'];
    		$result = $this->apps['main'][$action]($message, function($result_data) {
    			$object->send('app.receive_messages(data)', [
    				'data' => [
	    				'message_counter' => $message_counter, 
	    				'message' => $result_data
	    			]
    			]);
    		});
    		return NULL;
    	} else {
	    	$result = $this->apps['main'][$action]($message);
	    }

        return [
        	'message_counter' => $message_counter,
        	'message' => $result
        ];
    }
}


class combinations {
	
	private $values;

	public function __construct($values) {
		$this->values = $values;
	}

	public function start() {
		return $this->generate($this->values);
	}

	public function generate($values=NULL) {
		if($values->length == 0) {
			$result = [];
			return $result;
		}
		$result = [];
		foreach($values as $key => $value) {
			$subset = [...$values];
			$object->splice($subset, $key, 1);
			$result[] = $subset;
			$generated = $this->generate($subset);
			foreach($generated as $subset_value) {
				$index_of = $object->index_of($result, $subset_value);
				if($index_of == (-1)) {
					$result[] = $subset_value;
				}
			}
		}
		return $result;
	}
}

class permutations_count {

	public $count_value = 1;

	public function __construct($length) {
		$counter = $length;
		$count = 1;
		while($counter > 0) {
			$count = $counter*$count;
			$counter--;
		}
		$this->count_value = $count;
	}

}

class permutations_main {

	private $values;

	public function __construct($values) {
		$this->values = $values;
	}

	/*public function generate() {
		return $this->generate__cached($this->values);
	}*/

	public function generate($values=NULL) {
		if($values == NULL) {
			$values = $this->values;
		}
		if($values->length == 1) {
			return [$values];
		}
		$values = [...$values];
		$result = [];
		$object->log('call values length');
		$object->log($values->length);
		foreach($values as $key => $value) {
			$arrangement = [$value];
			$subset = [...$values];
			$object->splice($subset, $key, 1);
			$generated = $this->generate($subset);
			foreach($generated as $sub_arrangement) {
				$result[] = $object->concat($arrangement, $sub_arrangement);
			}
			$object->log('res: '.$result->length);
		}
		return $result;
	}
}

class permutations {

	private $values;

	public function __construct($values) {
		$this->values = $values;
	}

	public function generate_start() {
		return $this->generate($this->values);
	}

	public function generate($values=NULL, $callback=NULL) {
		if($values == NULL) {
			$values = $this->values;
		}
		$object->log('in generate');
		$object->log($object->toJSON($values));
		if($values->length == 1) {
			return [$values];
		}

		$values = [...$values];
		$result = [];

		$callback_wrap = function() {
			/*if($result->length == 1) {
				$callback($result
			}*/
			/*$permutations_sub = new permutations($result);
			$permutations_sub->permutations_sub(NULL, $callback);*/
			$callback($result);
		};



		foreach($values as $key => $value) {
			$arrangement = [$value];
			$subset = [...$values];
			$object->splice($subset, $key, 1);
			$object->log('in loop');
			$main_callback = async function($subset) {
				/*$generated = $this->generate($subset);*/
				$object->log('in main callback');
				$permutations_count = new permutations_count($values->length);
				$total_count = $permutations_count->count_value;

				$object->log('total count');
				$object->log($total_count);

				$permutations_sub = new permutations($subset);
				$permutations_sub->generate(NULL, function($generated) {
					$object->log('in generate');
					foreach($generated as $sub_arrangement) {
						$result[] = $object->concat($arrangement, $sub_arrangement);
					}
					$object->log($object->toJSON(['res' => $result, 'total' => $total_count]));
					if($result->length == $total_count) {
						$object->once($callback_wrap);
					}
				});
			};
			$main_callback($subset);
		}
		/*return $result;*/
	}
}


$base_instance = new base();






?>
