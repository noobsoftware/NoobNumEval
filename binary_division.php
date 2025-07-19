<?

class binary_division {
	
	private $evaluation;
		
	public function __construct($evaluation) {
		$this->evaluation = $evaluation;
	}
	
	private $quotient;
	
	public function get_quotient() {
		return $this->quotient;	
	}
		
	public function divide($n, $d) {
		$q = '0';
		$r = '0';
		$value_length = $object->strings->strlen($n);
		$i = $value_length-1;/*$this->evaluation->subtract($value_length, '1');*/
		while($this->evaluation->larger($i, 0)) {
			$r = $this->evaluation->bit_shift($r, '1', false);
			
			$r_chars = $object->strings->str_split($r);
			$n_chars = $object->strings->str_split($n);


			$set_r_index = $object->strings->strlen($r)-1;

			$set_n_index = ($object->strings->strlen($n)-$i)-1;

			$r_chars[$set_r_index] = $n_chars[$set_n_index];

			$r = $object->strings->implode('', $r_chars);
			
			if($this->evaluation->larger($r, $d)) {
				$r = $this->evaluation->binary_subtraction($r, $d);
				$q = $this->evaluation->get_digits($q);
				$count_q = $object->count($q);
				if($this->evaluation->larger($i, $count_q)) {
					$index = $count_q-1;
					while($this->evaluation->larger($i, $index, false)) {
						$q[$index] = '0';

						$index = $index+1;
					}
				}
				$q[$i] = '1';	

				$q = $object->strings->implode('', $object->array_reverse($q));
			}
			$i = $i-1;
		}
		$this->quotient = $q;
		return $r;
	}
	
	public function divide_alt($dividend, $divisor) {
		$dividend = $this->evaluation->change_base($dividend, '10', '2');
		$divisor = $this->evaluation->change_base($divisor, '10', '2');
		return $this->evaluation->change_base($this->evaluation->modulus($dividend, $divisor), '2');
	}
}

?>