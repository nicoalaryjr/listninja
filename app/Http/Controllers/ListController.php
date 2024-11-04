<?php

namespace App\Http\Controllers;

use App\Models\UserList;
use Illuminate\Http\Request;

class ListController extends Controller
{
    public function index()
    {
        $lists = auth()->user()->lists()->latest()->get();
        return view('lists.index', compact('lists'));
    }

    public function create()
    {
        return view('lists.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'type' => 'required|in:predefined,custom',
            'category' => 'required',
            'size' => 'required|in:5,10,20'
        ]);

        $list = auth()->user()->lists()->create($validated);

        return redirect()->route('lists.edit', $list)
            ->with('success', 'List created successfully!');
    }

    public function edit(UserList $list)
    {
        $this->authorize('update', $list);
        return view('lists.edit', compact('list'));
    }

    public function update(Request $request, UserList $list)
    {
        $this->authorize('update', $list);
        
        $validated = $request->validate([
            'title' => 'required|max:255',
            'category' => 'required',
            'is_published' => 'boolean'
        ]);

        $list->update($validated);

        return redirect()->route('lists.edit', $list)
            ->with('success', 'List updated successfully!');
    }

    public function destroy(UserList $list)
    {
        $this->authorize('delete', $list);
        $list->delete();

        return redirect()->route('lists.index')
            ->with('success', 'List deleted successfully!');
    }

    public function reorderItems(Request $request, UserList $list)
{
    $request->validate([
        'items' => 'required|array',
        'items.*.id' => 'required|exists:list_items,id',
        'items.*.position' => 'required|integer|min:1'
    ]);

    foreach ($request->items as $item) {
        $list->items()->where('id', $item['id'])->update([
            'position' => $item['position']
        ]);
    }

    return response()->json(['success' => true]);
}
}