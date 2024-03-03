public function index()
{
    $products = Product::all(); 
    return response()->json($products);
}
