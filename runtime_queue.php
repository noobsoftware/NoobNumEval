<?

class runtime_queue {

	/*private $runtime_items = [];*/

	private $callbacks = [];

	public function set_callback($callback) {
		$this->callbacks[] = $callback;
	}

	public function run($input, $output_start_value=NULL) {
		$state = [
			'next_input' => $input,
			'output_value' => $output_start_value,
			'terminating' => false
		];
		/*foreach($this->runtime_items as $runtime_item) {
			$runtime_item($state);
		}*/
		$callback = $this->callbacks[0];
		while(!$state['terminating']) {
			$callback($state);
		}
		return $state;
	}

	/*public function push($item) {
		$this->runtime_items[] = $item;
	}*/
}

class test {

	/*public function test_outer() {
		...prufa flokin koll? og sja hvort eg geti breytt syntaxinum
	}*/

	public function calculate_real_fraction($value, $decimal_points) {
		if($decimal_points <= 0) {
			return '';	
		}
		$fraction_values = $this->fraction_values($value);

		$division = $this->execute_divide($this->add_zeros('1', $object->strings->strlen($fraction_values[1])), $fraction_values[1]);
		$numerator = $this->multiply_total(['value' => $fraction_values[0], 'remainder' => '0/1'], $division);
		$denominator = $this->multiply_total(['value' => $fraction_values[1], 'remainder' => '0/1'], $division);
		$result = $numerator['value'];
		$result = $this->pad_zeros($result, $object->strings->strlen($fraction_values[1])-$object->strings->strlen($result), true);



		$result = $result.$this->calculate_real_fraction($numerator['remainder'], ($decimal_points-$object->strings->strlen($result)));
		return $result;
	}

	public function runtime_test() {
		/* Eitthvad flokid trace af recursive kollum til ad prufa hvort callbacks eda annad approach vaeri betra? */
		return false;
	}

	public function test_run() {
		$object->log('call test run');
		$queue = new runtime_queue();
		$object->log('call test run');

		$evaluation = new evaluation();

		$object->log('call test run');

		$callback = function($state) {
			$value = $state['next_input']['value'];
			$decimal_points = $state['next_input']['decimal_points'];
			if($decimal_points <= 0) {
				$state['terminating'] = true;
				/*$state['output_value'] = $state['output_value']*/
				return false;	
			}
			$fraction_values = $evaluation->fraction_values($value);

			$division = $evaluation->execute_divide($evaluation->add_zeros('1', $object->strings->strlen($fraction_values[1])), $fraction_values[1]);
			$numerator = $evaluation->multiply_total(['value' => $fraction_values[0], 'remainder' => '0/1'], $division);
			$denominator = $evaluation->multiply_total(['value' => $fraction_values[1], 'remainder' => '0/1'], $division);
			$result = $numerator['value'];
			$result = $evaluation->pad_zeros($result, $object->strings->strlen($fraction_values[1])-$object->strings->strlen($result), true);
			$state['output_value'] .= $result;
			$state['next_input']['value'] = $numerator['remainder'];
			$decimal_points = $state['next_input']['decimal_points'] -= 1;
		};

		$object->log('call test run');
		$queue->set_callback($callback);
		$object->log('call test run');

		$state = $queue->run([
			'value' => '1/3',
			'decimal_points' => 250
		]);

		$object->log($object->toJSON($state));

	}
	
}

?>