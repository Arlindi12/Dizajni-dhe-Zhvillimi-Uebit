<?php

class Controller {
  protected function view(string $path, array $data = []): void {
    View::render($path, $data);
  }

  protected function redirect(string $path): void {
    App::redirect($path);
  }
}
