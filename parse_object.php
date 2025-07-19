<?

class parse_object {
	public $children;
	private $parent;
	public $value;
	private $symbol;
	private $paranthesis;
	private $variable;
	private $contains_variables;
	private $parse_string;
	
	public function set_symbol($s) {
		$this->symbol = $s;
	}

	public function parse_object_parent($parent) {
		$this->children = [];
		$this->value =  [];
		$this->parent = $parent;
		$this->paranthesis = false;
	}

	public function init($parent) {
		$instance = new parse_object();
        $instance->parse_object_parent($parent);
        return $instance;
	}

	public function parse_object($method=NULL, $copy=NULL) {
		if($method == 'copy') { 			
			$this->children = [];
			$this->value = [];
			foreach($object->strings->str_split($copy->value) as $c) {
				$this->value[] = $c;
			}
			$this->parent = $copy->get_parent();
			$this->paranthesis = $copy->paranthesis;
		}
	}
	
	public function get_parse_string() {
		return $this->parse_string;   
	}
	public function set_parse_string($s) {
		$this->parse_string = $s;
	}
	public function set_paranthesis($value=true) {
		$this->paranthesis = $value;
	}
	public function is_paranthesis() {
		return $this->paranthesis;
	}
	public function set_variable($value) {
		$this->variable = $value;
	}
	public function get_variable() {
		return $this->variable;
	}
	public function set_contains_variables($contains_variables) {
		$this->contains_variables = $contains_variables;
	}
	public function get_contains_variables() {
		return $this->contains_variables;
	}
	public function append_value($value) {
		$this->value[] = $value;
	}
	public function set_value($value) {
		
		$this->value =  array();
		foreach($object->strings->str_split($value) as $c) {
			$this->value[] = $c;
		}
	}
	public function get_value() {
		$str = $this->get_string_value();
		if($object->strings->strlen($str) == 0) {
			return 0;
		}
		return $str;
	}
	public function get_string_value() {
		$str = '';
		foreach($this->value as $c) {
			$str .= $c;
		}
		return $str;
	}
	public function get_parent() {
		return $this->parent;
	}
	public function list_top() {
		return $this->children[$object->count($this->children) - 1];
	}
	public function add_object($child) {
		$child->parent = $this;
		$this->children[] = $child;
		return $this->list_top();
	}
	public function child() {
		$this->children[] = $this->init($this);
		return $this->list_top();
	}
	public function get_children() {
		return $this->children;
	}
	public function get_symbol() {
		return $this->symbol;
	}
	public $altered=false;
	public function alter_symbol($symbol, $mark_altered=true) {
		$this->symbol = $symbol;	
		$this->altered = $mark_altered;
	}
}

?>