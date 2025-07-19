<?

class trigonometry {
	
	public $evaluation;
	public $vector;
	
	public function __construct($evaluation=NULL) {
		/*if($evaluation != NULL) {
			$this->evaluation = $evaluation;		
		} else {
			$this->evaluation = new evaluation();	
		}*/
		$this->evaluation = $evaluation;
		$this->vector = new vector($this->evaluation);
	}
	
	public $accuracy;
	public $point;
	public $radius;
	
	public $cos;
	public $sin;
	public $cot;
	public $tan;
	public $csc;
	public $sec;
	public $excsc;
	public $cvs;
	public $versine;
		
	public function point($slope) {
		$slope_vector = $slope;
		$this->negative_values = $object->create();
		$this->point = $slope;

		
		if($this->evaluation->negative($this->point[0])) {
			$this->negative_values['cos'] = true;
			$this->negative_values['sec'] = true;
		}
		if($this->evaluation->negative($this->point[1])) {
			$this->negative_values['sin'] = true;
			$this->negative_values['csc'] = true;
		}
		if(!$this->evaluation->negative($this->point[0]) && $this->evaluation->negative($this->point[1])) {
			$this->negative_values['cot'] = true;
			$this->negative_values['tan'] = true;
		} else if($this->evaluation->negative($this->point[0]) && !$this->evaluation->negative($this->point[1])) {
			$this->negative_values['cot'] = true;
			$this->negative_values['tan'] = true;
		}
		$slope[0] = $this->evaluation->absolute($slope[0]);
		$slope[1] = $this->evaluation->absolute($slope[1]);
		$slope_vector = $slope;
		$this->point = $slope;
		if($slope_vector[0]['value'] == 0 && $this->evaluation->fraction_values($slope_vector[0]['remainder'])[0] == 0) {
			$result = [
				'cos' => ['value' => 0, 'remainder' => '0/1'],
				'sin' => ['value' => 1, 'remainder' => '0/1'],
				'cot' => ['value' => 0, 'remainder' => '0/1'],
				'tan' => 'NULL',
				'csc' => ['value' => 1, 'remainder' => '0/1'],
				'sec' => 'NULL',
				'versine' => ['value' => '1', 'remainder' => '0/1'],
				'crd' => 'NULL',
				'excsc' => 'NULL',
				'cvs' => 'NULL',
				'exsec' => 'NULL'
			];
			foreach($this->negative_values as $negative_index => $negative_value) {
				$result[$negative_index] = $this->evaluation->negative_value($result[$negative_index]);	
			}
			return $result;
		} else if($slope_vector[1]['value'] == 0 && $this->evaluation->fraction_values($slope_vector[1]['remainder'])[0] == 0) {
			$result = [
				'cos' => ['value' => 1, 'remainder' => '0/1'],
				'sin' => ['value' => 0, 'remainder' => '0/1'],
				'cot' => 'NULL',
				'tan' => ['value' => 0, 'remainder' => '0/1'],
				'csc' => 'NULL',
				'sec' => ['value' => 1, 'remainder' => '0/1'],
				'versine' => ['value' => '0', 'remainder' => '0/1'],
				'crd' => 'NULL',
				'excsc' => 'NULL',
				'cvs' => 'NULL',
				'exsec' => 'NULL'
			];
			foreach($this->negative_values as $negative_index => $negative_value) {
				$result[$negative_index] = $this->evaluation->negative_value($result[$negative_index]);	
			}
			return $result;
		} else {
			$power = ['value' => '2', 'remainder' => '0/1'];
			
			
			$denominator = $this->evaluation->add_total($this->evaluation->execute_power_whole($slope_vector[0], $power), $this->evaluation->execute_power_whole($slope_vector[1], $power));


			$length = $denominator;
			
			$length = $this->evaluation->execute_power($length, 2);		
			$length = $this->evaluation->execute_divide(['value' => '1', 'remainder' => '0/1'], $length);


			$point = $this->vector->stretch_vector($slope_vector, $length);
						
			$this->point = $point;
			
			
			if($this->evaluation->truncate_fractions_length > 0) {
				$this->point[0]['remainder'] = $this->evaluation->execute_shorten_fraction($this->point[0]['remainder']);
				$this->point[1]['remainder'] = $this->evaluation->execute_shorten_fraction($this->point[1]['remainder']);	
			}
			$res = $this->execute_outer_transformation();
			return $res;
		}
	}
	
	private $negative_values;
	
	public function polar_input($value) {
		$middle_value = ($math->pi()/4);
		if($value > $middle_value) {
			$value = $value - $middle_value;
			$proportion = $value / $middle_value;
		} else {
			$proportion = $value / $middle_value;
		}
	}
			
	public function polar($value) {
		$radian = $math->atan($value[1]/$value[0]);
		return $radian;
	}
	
	public function radian($value) {
		
		$x = $this->cosine($value);
		$y = $this->sine($value);
		
		return $this->point([$x, $y]);
	}
	
	public function slope($value) {
		$split = $object->strings->explode('/', $value);
		return $this->point([$split[0], $split[1]]);	
	}
	
	public $cot_precise = false;
	public $crd_precise = false;
	
	public function execute_outer_transformation() {
		$self = $this;
		$point_self = $self->point;
		$point_self = [[...$point_self[0]], [...$point_self[1]]];
		$vectors = [
			$point_self,
			[[...$self->point[0]], ['value' => 0, 'remainder' => '0/1']],
			[['value' => 0, 'remainder' => '0/1'], [...$self->point[1]]]
		];
		$rotated_vectors = [];
		foreach($vectors as $vector) {
			$rotated_vector = $this->vector->rotate($vector);
			$rotated_vectors[] = $rotated_vector;	
		}
		
		
		$top_placement = $rotated_vectors[0];
		$translation = ['value' => 1, 'remainder' => '0/1'];
		if($top_placement[1] != $this->point[1]) {
			if($this->evaluation->larger_total($this->point[1], $top_placement[1])) {
				$translation = $this->evaluation->execute_divide($this->point[1], $top_placement[1], false, false, false, true);		
			} else if($this->evaluation->larger_total($top_placement[1], $this->point[1])) {
				$translation = $this->evaluation->execute_divide($this->point[1], $top_placement[1], false, false, false, true);		
			}
		}

		
		$top_placement = $this->vector->stretch_vector($top_placement, $this->evaluation->absolute($translation));
		
		$tan = [...$top_placement];
		
		$baseline = $this->vector->reverse_vector($top_placement);
		
		$baseline = $this->vector->add_vector($this->point, $baseline);			
	
		$left_end = $this->vector->subtract_vector($this->point, $baseline);		
		$start_left_end = $this->vector->add_vector($this->point, $left_end);
		$start_point = $this->vector->subtract_vector($start_left_end, $this->point);
		
		
		
		
		$translation = 1;
		$translation_method = 2;


		$translation_value = [...$this->point[0]];
			
		$absolute_value = $this->evaluation->absolute($start_point[0]);
		$translation = $this->evaluation->execute_divide($absolute_value, $translation_value, false, false, false, true);		
	
		$translation_method = 1;
				
		$translation = $this->evaluation->execute_divide(1, $translation, false, false, false, true);
		$start_point_unaltered = [...$start_point];


		$start_point = $this->vector->stretch_vector($start_point, $this->evaluation->absolute($translation));
		
		$start_point_csc = [[...$start_point[0]], [...$start_point[1]]];
		$start_point_csc[0] = $this->evaluation->absolute($start_point_csc[0]);
		$start_point_csc[1] = $this->evaluation->absolute($start_point_csc[1]);		
		$cot_pos_csc = $this->vector->add_vector($this->point, $start_point_csc);
		
		
		$cot_pos = $this->vector->add_vector($this->point, $start_point);
		
		
		$cot = $this->vector->subtract_vector($cot_pos, $this->point);
		
		
		$cot[0] = $this->evaluation->absolute($cot[0]);
		$cot[1] = $this->evaluation->absolute($cot[1]);
				
		if($this->evaluation->truncate_fractions_length > 0) {
			$cot[0]['remainder'] = $this->evaluation->execute_shorten_fraction($cot[0]['remainder']);
			$cot[1]['remainder'] = $this->evaluation->execute_shorten_fraction($cot[1]['remainder']);
		}
		$this->cot = $this->vector->length_value($cot, $this->cot_precise);
		
		
		$sec = $this->vector->add_vector($this->point, $tan);		

		$this->sec = [...$sec[0]];		
		
		
		$this->csc = $cot_pos_csc[1];		
		$this->cos = $this->point[0];
		$this->sin = $this->point[1];
		$this->tan = $this->evaluation->execute_divide($this->sin, $this->cos, false, false, false, true);
		$this->versine = $this->evaluation->subtract_total(['value' => '1', 'remainder' => '0/1'], $this->cos);
		
		$crd_baseline = [$this->evaluation->add_total($this->cos, $this->versine), ['value' => '0', 'remainder' => '0/1']];
		$crd = $this->vector->subtract_vector($crd_baseline, $this->point);
		if($this->evaluation->truncate_fractions_length > 0) {
			$crd[0]['remainder'] = $this->evaluation->execute_shorten_fraction($crd[0]['remainder']);
			$crd[1]['remainder'] = $this->evaluation->execute_shorten_fraction($crd[1]['remainder']);
		}
		$crd = $this->vector->length_value($crd, $this->crd_precise);
		
		$excsc = $this->evaluation->subtract_total($this->csc, ['value' => '1', 'remainder' => '0/1']);
		$cvs = $this->evaluation->subtract_total(['value' => '1', 'remainder' => '0/1'], $this->sin);
		$this->excsc = $excsc;
		$this->cvs = $cvs;
		
		$exsec = $this->evaluation->subtract_total($this->sec, $this->evaluation->add_total($this->cos, $this->versine));
		
		$result = [
			'cos' => $this->cos,
			'sin' => $this->sin,
			'cot' => $this->cot,
			'tan' => $this->tan,
			'csc' => $this->csc,
			'sec' => $this->sec,
			'versine' => $this->versine,
			'crd' => $crd,
			'excsc' => $this->excsc,
			'cvs' => $cvs,
			'exsec' => $exsec
		];

		foreach($result as $index => $value) {
			$result[$index] = $this->evaluation->absolute($result[$index]);	
		}
		foreach($this->negative_values as $negative_index => $negative_value) {
			$result[$negative_index] = $this->evaluation->negative_value($result[$negative_index]);	
		}
		return $result;
	}
	
	public function set_sine_precision($sine_precision) {
		$this->sine_precision = $sine_precision;	
	}
	
	public $sine_precision = 10;
	
	public function sine($x, $precision=NULL) {
		if($precision === NULL) {
			$precision = $this->sine_precision;	
		}
		$result = $x;		
		$counter = 3;
		while($counter < $precision) {
			$power_term = $this->evaluation->execute_power_whole($x, $counter);
			$factorial_term = $this->evaluation->factorial($counter);	
			$addition = $this->evaluation->execute_divide($power_term, $factorial_term);
			$result = $this->evaluation->subtract_total($result, $addition);
			$counter += 2;
			$power_term = $this->evaluation->execute_power_whole($x, $counter);
			$factorial_term = $this->evaluation->factorial($counter);	
			$addition = $this->evaluation->execute_divide($power_term, $factorial_term);
			$result = $this->evaluation->add_total($result, $addition);
			$counter += 2;
			if($this->evaluation->truncate_fractions_length > 0) {
				$result['remainder'] = $this->evaluation->execute_shorten_fraction($result['remainder']);	
			}
		}
		return $result;
	}
	
	public function cosine($x, $precision=NULL) {
		if($precision === NULL) {
			$precision = $this->sine_precision;	
		}
		$result = ['value' => 1, 'remainder' => '0/1'];
		$counter = 2;
		while($counter < $precision) {
			$power_term = $this->evaluation->execute_power_whole($x, $counter);
			$factorial_term = $this->evaluation->factorial($counter);	
			$addition = $this->evaluation->execute_divide($power_term, $factorial_term);
			$result = $this->evaluation->subtract_total($result, $addition);
			$counter += 2;
			$power_term = $this->evaluation->execute_power_whole($x, $counter);
			$factorial_term = $this->evaluation->factorial($counter);	
			$addition = $this->evaluation->execute_divide($power_term, $factorial_term);
			$result = $this->evaluation->add_total($result, $addition);
			$counter += 2;
			if($this->evaluation->truncate_fractions_length > 0) {
				$result['remainder'] = $this->evaluation->execute_shorten_fraction($result['remainder']);
			}
		}
		return $result;
	}
	
	public function angle($a, $b) {
		$dot_product = $this->vector->dot_product($a, $b);
		$length_a = $this->vector->length_value($a, true);
		$length_b = $this->vector->length_value($b, true);
		$denominator = $this->evaluation->multiply_total($length_a, $length_b);
		$result = $this->evaluation->execute_divide($dot_product, $denominator, true);
		return $result;
	}
	
	public function cosine_vector($a) {
		return $this->angle($a, [['value' => '1', 'remainder' => '0/1'], ['value' => '0', 'remainder' => '0/1']]);	
	}

	public function arctan($x, $precision=NULL) {
		if($precision === NULL) {
			$precision = $this->sine_precision;	
		}
		$result = $x;		
		$counter = 3;
		while($counter < $precision) {
			$power_term = $this->evaluation->execute_power_whole($x, $counter);
			$factorial_term = $counter;			
			$addition = $this->evaluation->execute_divide($power_term, $factorial_term);
			$result = $this->evaluation->subtract_total($result, $addition);
			$counter += 2;
			$power_term = $this->evaluation->execute_power_whole($x, $counter);
			$factorial_term = $counter;			
			$addition = $this->evaluation->execute_divide($power_term, $factorial_term);
			$result = $this->evaluation->add_total($result, $addition);
			$counter += 2;
			if($this->evaluation->truncate_fractions_length > 0) {
				$result['remainder'] = $this->evaluation->execute_shorten_fraction($result['remainder']);	
			}
		}
		return $result;
	}
	
	public function compute_pi($precision) {
		$arctan_value_a = $this->arctan(['value' => '0', 'remainder' => '1/7'], $precision);
		$arctan_value_a = $this->evaluation->multiply_total($arctan_value_a, ['value' => '20', 'remainder' => '0/1']);
		$arctan_value_b = $this->arctan(['value' => '0', 'remainder' => '3/79'], $precision);	
		$arctan_value_b = $this->evaluation->multiply_total($arctan_value_b, ['value' => '8', 'remainder' => '0/1']);
		$result = $this->evaluation->add_total($arctan_value_a, $arctan_value_b);
		return $result;
	}
	
	public function arccot($x, $precision=NULL) {
		if($precision === NULL) {
			$precision = $this->sine_precision;	
		}
		$arctan = $this->arctan($x, $precision);
		$pi_value = $this->evaluation->execute_divide($this->evaluation->pi(), 2);
		$subtraction = $this->evaluation->subtract_total($pi_value, $arctan);
		return $subtraction;
	}
	
	public function arccos($x, $precision=NULL) {
		if($precision === NULL) {
			$precision = $this->sine_precision;	
		}
		$arcsin = $this->arcsin($x, $precision);
		$pi_value = $this->evaluation->execute_divide($this->evaluation->pi(), 2);
		$subtraction = $this->evaluation->subtract_total($pi_value, $arcsin);
		return $subtraction;
	}
	
	public function arcsin($x, $precision=NULL) {
		if($this->evaluation->larger_total($x, ['value' => '1', 'remainder' => '0/1'], false)) {
			return false;	
		}
		if($precision === NULL) {
			$precision = $this->sine_precision;	
		}
		$denominator_value_a = $this->evaluation->subtract_total(['value' => '1', 'remainder' => '0/1'], $x);
		$denominator_value_b = $this->evaluation->add_total(['value' => '1', 'remainder' => '0/1'], $x);
		$denominator_value_a = $this->evaluation->execute_power($denominator_value_a, 2);
		$denominator_value_b = $this->evaluation->execute_power($denominator_value_b, 2);
		$denominator = $this->evaluation->multiply_total($denominator_value_a, $denominator_value_b);
		if($this->evaluation->equals_zero($denominator)) {
			return $this->evaluation->execute_divide($this->evaluation->pi(), '2');	
		}
		$input_value = $this->evaluation->execute_divide($x, $denominator);
		if($this->evaluation->truncate_fractions_length > 0) {
			$input_value['remainder'] = $this->evaluation->execute_shorten_fraction($input_value['remainder']);	
		}
		$atan = $this->arctan($input_value, $precision);
		return $atan;	
	}
}

?>