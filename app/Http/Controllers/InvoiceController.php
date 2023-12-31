<?php

namespace App\Http\Controllers;

use App\Models\Counter;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function get_all_invoices()
    {
        $invoices = Invoice::with('customer')->orderBy('id', 'DESC')->get();

        return response()->json([
            'invoices' => $invoices
        ], 200);
    }

    public function search_invoices(Request $request)
    {
        $search = $request->get('s');
        if ($search != null) {
            $invoices = Invoice::with(('customer'))
                ->where('id', 'LIKE', "%$search")
                ->get();
            return response()->json([
                'invoices' => $invoices
            ], 200);
        } else {
            return $this->get_all_invoices();
        }
    }

    public function create_invoice(Request $request)
    {
        $counter = Counter::where('key', 'invoice')->first();
        $random = Counter::where('key', 'invoice')->first();

        $invoice = Invoice::orderBy('id', 'DESC')->first();
        if ($invoice) {
            $invoice = $invoice->id + 1;
            $counters = $counter->value + $invoice;
        } else {
            $counters = $counter->value;
        }

        $formData = [
            'number' => $counter->prefix . $counters,
            'customer_id' => null,
            'customer' => null,
            'date' => date('Y-m-d'),
            'due_date' => null,
            'reference' => null,
            'discount' => 0,
            'terms_and_conditions' => 'Default terms and conditions',
            'items' => [
                [
                    'product_id' => null,
                    'product' => null,
                    'unit_price' => 0,
                    'quantity' => 1
                ]
            ]
        ];
        return response()->json($formData);
    }

    public function add_invoice(Request $request)
    {
        $invoice_item = $request->input("invoice_item");

        $invoice_data["sub_total"] = $request->input("subTotal");
        $invoice_data["total"] = $request->input("total");
        $invoice_data["customer_id"] = $request->input("customerId");
        $invoice_data["number"] = $request->input("number");
        $invoice_data["date"] = $request->input("date");
        $invoice_data["due_date"] = $request->input("due_date");
        $invoice_data["discount"] = $request->input("discount");
        $invoice_data["reference"] = $request->input("reference");
        $invoice_data["terms_and_conditions"] = $request->input("terms_and_conditions");

        $invoice = Invoice::create($invoice_data);

        foreach (json_decode($invoice_item) as $item) {
            $itemData['product_id'] = $item->id;
            $itemData['invoice_id'] = $invoice->id;
            $itemData['quantity'] = $item->quantity;
            $itemData['unit_price'] = $item->unit_price;

            InvoiceItem::create($itemData);
        };
    }

    public function show_invoice($id)
    {
        $invoice = Invoice::with(['customer', 'invoice_items.product'])->find($id);
        return response()->json([
            'invoice' => $invoice
        ], 200);
    }
}
