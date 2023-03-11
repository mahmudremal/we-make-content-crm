<?php
/**
 * The OpenAI ChatGPT-3.
 * https://www.npmjs.com/package/openai
 * https://www.npmjs.com/package/chatgpt
 * 
 * @package WeMakeContentCMS
 */
namespace WEMAKECONTENTCMS_THEME\Inc;
use WEMAKECONTENTCMS_THEME\Inc\Traits\Singleton;

class Gpt3 {
	use Singleton;
	private $base;
	protected function __construct() {
    $this->base = [];
		$this->setup_hooks();

	}
	protected function setup_hooks() {
	}
  
}
