<?

class vector {
	
	private $evaluation;
	
	public function __construct($evaluation) {
		$this->evaluation = $evaluation;
	}
	
	public function compare_vectors($u, $v) {
		if($u[0] == $v[0] && $u[1] == $v[1]) {
			return true;
		}
		return false;
	}

	/*public function angle_between_vectors($u, $v) {
		if($this->compare_vectors($u, $v)) {
			return 0;
		}
		$dot = $this->dot_product($u, $v);
		$u_distance = $this->vector_distance($u);
		$v_distance = $this->vector_distance($v);
		$division = $this->evaluation->add_total($u_distance, $v_distance);//$u_distance + $v_distance;
		$result = $this->evaluation->execute_divide($dot, $division);//$dot / $division;
		$result = $math->acos($this->evaluation->quick_fraction($result));
		$result = $this->multiply_total($result, $this->evaluation->whole_common('57.2957795')); 
		return $result;
	}*/

	public function flip_vector($u) {
		$vector = [[...$u[0]], $this->evaluation->negative_value($u[1])];
		return $vector;
	}

	public function flip_x($u) {
		$vector = [$this->evaluation->negative_value($u[0]), [...$u[1]]];
		return $vector;
	}

	public function reverse_vector($v) {
		$u = [$this->evaluation->negative_value($v[0]), $this->evaluation->negative_value($v[1])];
		return $u;
	}

	public function reset_vector_length($point, $length) {
		$initial_length = $this->vector_distance($point, [0,0]);
		$fraction = $this->evaluation->execute_divide($length, $initial_length);
		$new_point = $this->reset_distance($point, $fraction);
		$new_distance = $this->vector_distance($new_point, [0,0]);
		return $new_point;
	}

	public function dot_product($u, $v) {
		return $this->add($this->evaluation->multiply_total($u[0], $v[0]), $this->evaluation->multiply_total($u[1], $v[1]));
	}
	
	public function projection($u, $v) {
		$division = $this->evaluation->execute_power_whole($this->distance($v[0], $v[1]), 2);
		if($division['value'] == 0 && $this->evaluation->fraction_values($division['remainder'])[0] == 0) {
			return 0;
		}
		$vector = [[...$v[0]], [...$v[1]]];
		$mult = $this->evaluation->execute_divide($this->dot_product($u, $v), $division);
		$vector[0] = $this->evaluation->multiply_total($vector[0], $mult);
		$vector[1] = $this->evaluation->multiply_total($vector[1], $mult);
		$vector = $this->shorten_vector($vector);
		return $vector;
	}
	
	public function vector_distance($u, $v=[['value' => 0, 'remainder' => '0/1'], ['value' => '0', 'remainder' => '0/1']]) {
		return $this->distance($u[0], $u[1], $v[0], $v[1]);
	}
	
	public function vector_sum($u, $value) {
		$vector = [[...$u[0]], [...$u[1]]];
		$vector[0] = $this->evaluation->add_total($vector[0], $value);
		$vector[1] = $this->evaluation->add_total($vector[1], $value);
		$vector = $this->shorten_vector($vector);
		return $vector;
	}
	
	public function add_vector($u, $v) {
		$vector = [[...$u[0]], [...$u[1]]];
		$vector[0] = $this->evaluation->add_total($vector[0], $v[0]);
		$vector[1] = $this->evaluation->add_total($vector[1], $v[1]);
		$vector = $this->shorten_vector($vector);
		return $vector;
	}
	
	public function distance($x_from, $y_from, $x_to=['value' => 0, 'remainder' => '0/1'], $y_to=['value' => 0, 'remainder' => '0/1']) {
		$term_a = $this->evaluation->subtract_total($x_from, $x_to);
		$term_a = $this->evaluation->execute_power_whole($term_a, ['value' => '2', 'remainder' => '0/1']);
		
		$term_b = $this->evaluation->subtract_total($y_from, $y_to);
		$term_b = $this->evaluation->execute_power_whole($term_b, ['value' => '2', 'remainder' => '0/1']);
		
		$total_term = $this->evaluation->add_total($term_a, $term_b);
		/*$value = $this->evaluation->whole_common($math->sqrt($this->evaluation->quick_numeric($total_term)));*/
		$value = $this->evaluation->execute_power($total_term, 2);
		
		return $value;
	}
	
	public function length_value($u) {
		return $this->distance($u[0], $u[1]);	
	}
	
	public function normalize_vector($v) {
		$length = $this->vector_distance($v);
		if($length == 0) {
			return $v;	
		}
		$vector = [[...$v[0]], [...$v[1]]];
		$vector[0] = $this->evaluation->execute_divide($vector[0], $length);
		$vector[1] = $this->evaluation->execute_divide($vector[1], $length);
		return $vector;
	}
	
	public function subtract_vector($u, $v) {
		$vector = [[...$u[0]], [...$u[1]]];
		$vector[0] = $this->evaluation->subtract_total($vector[0], $v[0]);
		$vector[1] = $this->evaluation->subtract_total($vector[1], $v[1]);
		$vector = $this->shorten_vector($vector);
		return $vector;
	}

	public function sum_vector($u, $v) {
		$vector = [[...$u[0]], [...$u[1]]];
		$vector[0] = $this->evaluation->add_total($vector[0], $v[0]);
		$vector[1] = $this->evaluation->add_total($vector[1], $v[1]);
		$vector = $this->shorten_vector($vector);
		return $vector;
	}
	
	public function stretch_vector($v, $unit_value) {
		if(!$object->item_is_array($unit_value)) {
			$unit_value = ['value' => $unit_value, 'remainder' => '0/1'];
		}
		$vector = [[...$v[0]], [...$v[1]]];
		$vector[0] = $this->evaluation->multiply_total($vector[0], $unit_value);
		$vector[1] = $this->evaluation->multiply_total($vector[1], $unit_value);
		$vector = $this->shorten_vector($vector);
		return $vector;
	}
	
	public function reflection($d, $n) {
		$n = [[...$n[0]], [...$n[1]]];
		$n = $this->normalize_vector($n);
		$dot = $this->dot_product($d, $n);
		$dot = $this->evaluation->multiply_total(['value' => 2, 'remainder' => '0/1'], $dot);
		$stretch = $this->stretch_vector($n, $dot);
		$subtract = $this->subtract_vector($d, $stretch);
		$subtract = $this->shorten_vector($subtract);
		return $subtract;
	}
	
	public function rotate($u, $clockwise=true) {
		$v = [[...$u[1]], $this->evaluation->negative_value($u[0])];
		return $v;
	}
	
	public function shorten_vector($vector) {
		$vector[0]['remainder'] = $this->evaluation->execute_shorten_fraction($vector[0]['remainder']);
		$vector[1]['remainder'] = $this->evaluation->execute_shorten_fraction($vector[1]['remainder']);	
		return $vector;
	}
}

?>