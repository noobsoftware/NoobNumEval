<?

namespace NumEval;

class quadratic_solver {
	
	private $a;
	private $b;
	private $c;
	
	private $evaluation;
	
	public function __construct($a, $b, $c, $evaluation) {
		$this->a = $a;
		$this->b = $b;
		$this->c = $c;
		$this->evaluation = $evaluation;	
	}
	
	public function solve($precise=false) {
		$b_squared = $this->evaluation->execute_power_whole($this->b, 2)['value'];
		$root_value = $this->evaluation->result($this->a, $this->c);
		$root_value = $this->evaluation->result(4, $root_value);
		$root_value = $this->evaluation->subtract($b_squared, $root_value);
		$root_value_unaltered = $root_value;
		$root_value = $this->evaluation->root($root_value, 2);
		if($root_value === false) {
			if($precise) {
				return false;
			}
			$root_value = $this->evaluation->execute_power(array('value' => $root_value_unaltered, 'remainder' => '0/1'), 2);
			if($root_value === NULL) {
				return false;	
			}
		} 
		$negative_b = $this->evaluation->negative_value($this->b);
		$numerator_a;
		$numerator_b;
		if(!$object->item_is_array($root_value)) {
			$numerator_a = $this->evaluation->add($negative_b, $root_value);
			$numerator_b = $this->evaluation->subtract($negative_b, $root_value);
		} else {
			$numerator_a = $this->evaluation->add_total(array('value' => $negative_b, 'remainder' => '0/1'), $root_value);	
			$numerator_b = $this->evaluation->subtract_total(array('value' => $negative_b, 'remainder' => '0/1'), $root_value);
		}
		
		$denominator = $this->evaluation->result(2, $this->a);
		
		$value_a = $this->evaluation->execute_divide($numerator_a, $denominator);
		$value_b = $this->evaluation->execute_divide($numerator_b, $denominator);
		
		return array(
			$value_a,
			$value_b
		);
	
	}
}

?>