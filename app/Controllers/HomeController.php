<?php

class HomeController extends Controller {
  public function index(): void {
    Auth::requireLogin();

    $pageTitle = "BookNest | Home";
    $activePage = "home";

    $slides = [
      ["image" => "Photo6.jpg", "caption" => ""],
      ["image" => "Photo2.jpg", "caption" => "Libri më i lexuar: Përtej Hijes"],
      ["image" => "Photo3.jpg", "caption" => ""],
      ["image" => "Photo4.jpg", "caption" => "Libri i javës: 1984"],
      ["image" => "Photo5.jpg", "caption" => ""],
    ];

    $books = Book::latest(3);

    $this->view("pages/index", compact("pageTitle","activePage","slides","books"));
  }
}
