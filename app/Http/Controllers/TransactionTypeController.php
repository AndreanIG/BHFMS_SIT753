<?php

namespace App\Http\Controllers;

use App\Models\TransactionType;
use Illuminate\Http\Request;

class TransactionTypeController extends Controller
{
    public function getAllTransactionTypePage(Request $request){
        $transactionTypes = TransactionType::when($request->searchQuery, function ($query, $searchQuery) {
            if ($searchQuery == '') {
                $query;
            } else {
                $query->where('transaction_type_name', 'like', '%'. $searchQuery . '%');
            }
        })->paginate(5)->withQueryString();
        return inertia('TransactionType/ListTransactionType', [
            'transactionTypes' => $transactionTypes
        ]);
    }

    public function getTransactionTypeCreate(){
        return inertia('TransactionType/CreateTransactionType', [
        ]);
    }

    public function TransactionTypeCreate(Request $request){
        $validation = $request->validate([
            'name' => ['required', 'max:200', 'min:3','unique:transaction_types,transaction_type_name,'],
        ]);

        TransactionType::create([
            'transaction_type_name' => $request->name,
        ]);

        return redirect('/transactionTypeAll')->with('message', 'Success Creating new Transaction Type');
    }

    public function getTransactionTypeUpdate(Request $request){
        $currTransactionType = TransactionType::find($request->id);
        return inertia('TransactionType/UpdateTransactionType', [
            'transactionType' => $currTransactionType,
        ]);
    }

    public function TransactionTypeUpdate(Request $request){

        $validation = $request->validate([
            'name' => ['required', 'max:200', 'min:3','unique:transaction_types,transaction_type_name,' . $request->id],
        ]);

        TransactionType::where('id','=',$request->id)->update([
            'transaction_type_name' => $request->name,
        ]);

        return redirect('/transactionTypeAll')->with('message', 'Success Updating Transaction Type');
    }

    public function TransactionTypeDelete(Request $request){
        $currTransactionType = TransactionType::where('id','=',$request->id)->first();

        $currTransactionType->delete();

        return redirect('/transactionTypeAll')->with('message', 'Success Deleting Transaction Type');
    }
}
