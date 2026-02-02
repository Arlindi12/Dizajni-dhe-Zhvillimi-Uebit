<?php

class BooksController extends Controller {
  public function index(): void {
    Auth::requireLogin();

    $pageTitle = "BookNest | Librat";
    $activePage = "books";

    $q = trim($_GET["q"] ?? "");
    $books = Book::all($q);

    $this->view("pages/books", compact("pageTitle","activePage","books","q"));
  }

  public function details(): void {
    Auth::requireLogin();

    $pageTitle = "BookNest | Detajet e librit";
    $activePage = "books";

    $slug = trim($_GET["book"] ?? "");
    $book = $slug !== "" ? Book::findBySlug($slug) : null;

    if (!$book) {
      // fallback first book
      $all = Book::all("");
      $book = $all[0] ?? null;
    }

    if (!$book) die("Nuk ka libra në databazë.");

    $this->view("pages/book_details", compact("pageTitle","activePage","book"));
  }
}
