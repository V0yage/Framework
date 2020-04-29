<?php
	namespace Views;
	
	class View {
		private $templatePath;
		
		public function __construct(string $templatePath) {
			$this->templatePath = $templatePath;
		}
		
		public function renderHtml(string $templateName, array $vars = [], int $responseCode = 200) {
			http_response_code($responseCode);
			extract($vars);
			
			ob_start();
			include $this->templatePath . '/' . $templateName;
			$buffer = ob_get_clean();
			
			echo $buffer;
		}
	}