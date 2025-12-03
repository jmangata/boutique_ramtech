<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Commande;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class CommandeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Commande::with(['user', 'lignes.produit']);

        // Filtre par statut
        if ($request->has('statut') && $request->statut) {
            $query->where('statut_commande', $request->statut);
        }

        // Filtre par recherche
        if ($request->has('search') && $request->search) {
            $query->search($request->search);
        }

        // Filtre par date
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

       $commandes = $query->orderBy('created_at', 'desc')->paginate(15);


        $stats = [
            'total' => Commande::count(),
            'en_attente' => Commande::pending()->count(),
            'confirmees' => Commande::confirmed()->count(),
            'expediees' => Commande::shipped()->count(),
            'livrees' => Commande::delivered()->count(),
            'annulees' => Commande::cancelled()->count(),
        ];

        return view('commandes.index', compact('commandes', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $users = User::all();
        return view('commandes.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'montant_total' => 'required|numeric|min:0',
            'statut_commande' => 'required|in:en_attente,confirmee,expediee,livree,annulee',
            'adresse_livraison' => 'required|string|max:1000',
        ]);

        Commande::create($validated);

        return redirect()->route('commandes.index')
                        ->with('success', 'Commande créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Commande $commande): View
    {
        $commande->load(['user', 'lignes.produit.category']);

        return view('commandes.show', compact('commande'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Commande $commande): View
    {
        $users = User::all();
        $commande->load('user');
        
        return view('commandes.edit', compact('commande', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Commande $commande): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'montant_total' => 'required|numeric|min:0',
            'statut_commande' => 'required|in:en_attente,confirmee,expediee,livree,annulee',
            'adresse_livraison' => 'required|string|max:1000',
        ]);

        $commande->update($validated);

        return redirect()->route('commandes.index')
                        ->with('success', 'Commande mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Commande $commande): RedirectResponse
    {
        // Vérifier si la commande peut être supprimée
        if (!in_array($commande->statut_commande, ['en_attente', 'annulee'])) {
            return redirect()->route('commandes.index')
                            ->with('error', 'Impossible de supprimer une commande ' . $commande->formatted_statut . '.');
        }

        $commande->delete();

        return redirect()->route('commandes.index')
                        ->with('success', 'Commande supprimée avec succès.');
    }

    /**
     * Update order status.
     */
    public function updateStatus(Request $request, Commande $commande): RedirectResponse
    {
        $request->validate([
            'statut_commande' => 'required|in:en_attente,confirmee,expediee,livree,annulee',
        ]);

        $ancien_statut = $commande->formatted_statut;
        $commande->update(['statut_commande' => $request->statut_commande]);

        return redirect()->route('commandes.show', $commande)
                        ->with('success', "Statut de la commande modifié de '$ancien_statut' à '{$commande->formatted_statut}'.");
    }

    /**
     * Cancel an order.
     */
    public function cancel(Commande $commande): RedirectResponse
    {
        if (!$commande->can_be_cancelled) {
            return redirect()->route('commandes.show', $commande)
                            ->with('error', 'Impossible d\'annuler cette commande dans son état actuel.');
        }

        $commande->update(['statut_commande' => 'annulee']);

        return redirect()->route('commandes.show', $commande)
                        ->with('success', 'Commande annulée avec succès.');
    }

    /**
     * Display user's orders.
     */
    public function userOrders(Request $request): View
    {
        $commandes = Commande::where('user_id', Auth::id())
                            ->with('lignes.produit')
                            ->orderBy('created_at', 'desc')
                            ->paginate(10);

        return view('commandes.user-orders', compact('commandes'));
    }

    /**
     * Search orders.
     */
    public function search(Request $request): View
    {
        $search = $request->input('search');
        
        $commandes = Commande::search($search)
                            ->with(['user', 'lignes.produit'])
                            ->orderBy('created_at', 'desc')
                            ->paginate(15);

        $stats = [
            'total' => Commande::count(),
            'en_attente' => Commande::pending()->count(),
            'confirmees' => Commande::confirmed()->count(),
            'expediees' => Commande::shipped()->count(),
            'livrees' => Commande::delivered()->count(),
            'annulees' => Commande::cancelled()->count(),
        ];

        return view('commandes.index', compact('commandes', 'stats', 'search'));
    }

    /**
     * Display orders by status.
     */
    public function byStatus($status): View
    {
        $method = match($status) {
            'en_attente' => 'pending',
            'confirmees' => 'confirmed',
            'expediees' => 'shipped',
            'livrees' => 'delivered',
            'annulees' => 'cancelled',
            default => null
        };

        if (!$method) {
            abort(404);
        }

        $commandes = Commande::{$method}()
                            ->with(['user', 'lignes.produit'])
                            ->orderBy('created_at', 'desc')
                            ->paginate(15);

        $stats = [
            'total' => Commande::count(),
            'en_attente' => Commande::pending()->count(),
            'confirmees' => Commande::confirmed()->count(),
            'expediees' => Commande::shipped()->count(),
            'livrees' => Commande::delivered()->count(),
            'annulees' => Commande::cancelled()->count(),
        ];

        return view('commandes.index', compact('commandes', 'stats', 'status'));
    }
}