<?

class prime_factorization {
	
	private $evaluation;
	private $binary_modulus;
	
	public function __construct($evaluation) {
		$this->evaluation = $evaluation;
		$this->binary_modulus = new binary_modulus($this->evaluation, '0');	
		$this->marked_intervals = $object->create();
	}
	
	private $prime_factors = [];
	private $limit = 0;

	private $intermediate_results = [];
	
	public function factor($value) {
		/*if($object->isset($this->intermediate_results[$value])) {
			$this->prime_factors = array_merge($this->prime_factors, $value);
			return $this->prime_factors;	
		}*/
		$sub_factors = [$value];

		while($sub_factors->length > 0) {
			$value = $object->array_pop($sub_factors);
			$digit_first = $this->evaluation->get_digits($value)[0];
			if($digit_first == '5') {
				$this->prime_factors[] = '5';
				$division = $this->evaluation->execute_divide($value, '5')['value'];
				/*return $this->factor($division);	*/
				$sub_factors[] = $division;
			} else if($value == '0') {
				return false;	
			} else if($this->evaluation->prime($value, NULL, true)) {
				$this->prime_factors[] = $value;
				/*return $this->prime_factors;*/	
			} else if($this->evaluation->even($value)) {
				$this->prime_factors[] = '2';
				$division = $this->evaluation->execute_divide($value, '2')['value'];
				/*return $this->factor($division);	*/
				$sub_factors[] = $division;
			} else {
				
				$a['root'] = $this->evaluation->root($value, '2');
				if($a['root'] === (-1)) {
					$a['root'] = $this->evaluation->root_closest_result;	
				}
				$a['value'] = $this->evaluation->result($a['root'], $a['root']);
				
				if($a['value'] == $value) {
					if($this->evaluation->prime($a['root'], NULL, true)) {
						$this->prime_factors[] = $a['root'];
						$this->prime_factors[] = $a['root'];
						/*return $this->prime_factors;*/
					} else {
						/*$this->factor($a['root']); 
						return $this->factor($a['root']);*/
						$sub_factors[] = $a['root'];
						$sub_factors[] = $a['root'];
					}
				} else {
					$interval = $this->factor_sub($value);
					$b = $this->find_value($value, $interval);
					$interval = $b['interval'];
					$b = $b['result'];
									
					$a = $this->evaluation->add($value, $this->evaluation->result($b, $b));
					$a = $this->evaluation->root($a, '2');
													
					$term_a = $this->evaluation->add($a, $b);
					$term_b = $this->evaluation->subtract($a, $b);
									
					/*$this->factor($term_a);
					return $this->factor($term_b);*/
					$sub_factors[] = $term_a;
					$sub_factors[] = $term_b;
				}
			}
		}
		return $this->prime_factors;
	}
		
	public function find_maximum_interval($value) {
		$root = $this->evaluation->root($value, '2');
		$root = $this->evaluation->root_closest_result;
		$root = $this->evaluation->add($root, '5');
		return $root;	
	}
	
	public function find_interval_range($value) {
		$root_value = $this->evaluation->add($value, '4');
		$start_root = $this->evaluation->root($root_value, 2);
		$start_root = $this->evaluation->root_closest_result;
		
		$start_root = $this->evaluation->add($start_root, '1');
		$root_squared = $this->evaluation->result($start_root, $start_root);
		
		return $start_root;
	}
	
	private $marked_intervals = NULL;
	
	public function find_interval($value) {
		$binary_modulus = new binary_modulus($this->evaluation, '0');
		
		$maximum_interval = $this->find_interval_range($value);
		
		$interval = '1';
		$modulus_value = '1';
		while(true) {
			$this->marked_intervals[$interval] = true;
			$modulus_value = $value;	
			
			$divider = $this->evaluation->add($interval, '1');
			
			$modulus_value = $this->evaluation->modulus($modulus_value, $divider);
			
			$modulus_value_sub = '1';
			if($modulus_value == '0') {
				return $interval;	
			}
			$multiplier = '2';
			$multiplied_value = $interval;
			if($this->evaluation->larger($interval, '1', false)) {
				while($this->evaluation->larger($maximum_interval, $multiplied_value)) {
					$multiplied_value = $this->evaluation->result($multiplier, $interval);
					$this->marked_intervals[$multiplied_value] = true;
					
					$multiplier = $this->evaluation->add($multiplier, '1');
				}
			}
			
			while($object->isset($this->marked_intervals[$interval])) {
				$interval = $this->evaluation->add($interval, '1');
			}
			
		}
		return $this->evaluation->subtract($interval, '1');
	}
	
	public function find_interval_alt($value) {
		$interval = '1';
		$modulus_value = '1';
		$no_remainder = false;
		while($modulus_value != '0') {
			$modulus_value = $this->evaluation->add($value, '1');
			$modulus_value = $this->evaluation->subtract($modulus_value, $this->evaluation->result($interval, $interval));	
			
			$divider = $this->evaluation->add($this->evaluation->result('2', $interval), '2');
			
			$modulus_value = $this->evaluation->modulus($modulus_value, $divider);
			
			$interval = $this->evaluation->add($interval, '1');
		}
		return $this->evaluation->subtract($interval, '1');
	}
	
	public function find_value($value, $interval) {
		$stop = false;
		$coefficient = $this->evaluation->add($this->evaluation->result($interval, '2'), '2');
		
		$a_1 = $this->evaluation->add($interval, '3');
		$constant = $this->evaluation->result($a_1, $a_1);
		$constant = $this->evaluation->subtract($constant, '4');
		
	
		$result = $this->evaluation->subtract($value, $constant);
		$result = $this->evaluation->execute_divide($result, $coefficient);
		if($this->evaluation->fraction_values($result['remainder'])[0] == 0) {
			$stop = true;	
		}
		$result = $result['value'];
		$result = $this->evaluation->add($result, '2');
		return ['result' => $result, 'interval' => $interval];
	}
	
	public function find_a($value) {
		$value_2 = $this->evaluation->result($value, $value);
		$rational_roots = $this->evaluation->list_rational_roots($value, $value_2);
		$last_root = NULL; 	
		foreach($rational_roots as $rational_root) {
			if($last_root != NULL) {
				$difference = $this->evaluation->subtract($rational_root['value'], $value);
				$difference_root = $this->evaluation->root($difference, '2');
				if($difference_root !== (-1)) {
					return [
						'a' => $rational_root,
						'b' => ['value' => $difference, 'root' => $difference_root]
					];
				}
			}
			$last_root = $rational_root;	
		}
	}
	
	public function pollard_sub($value, $n) {
		$x = $this->evaluation->result($value, $value);
		
		$x = $this->evaluation->add($x, '1');
		$x = $this->evaluation->modulus($x, $n);
		return $x;
	}
	
	public function abs_modulus($a, $b) {
		if($this->evaluation->larger($a, $b)) {
			return $this->evaluation->modulus($a, $b);	
		}
		return $this->evaluation->modulus($b, $a);
	}
		
	public function factor_sub($n) {
		$x = '2';
		$y = '2';
		$d = '1';
		$stop = false;
		while($d == '1' || $d == $n) {
			$x = $this->pollard_sub($x, $n);
			$y = $this->pollard_sub($this->pollard_sub($y, $n), $n);
			$d = $this->evaluation->gcd($this->evaluation->absolute($this->evaluation->subtract($x, $y)), $n);	
			
		}
		return $this->evaluation->subtract($d, '1');
	}

}

?>