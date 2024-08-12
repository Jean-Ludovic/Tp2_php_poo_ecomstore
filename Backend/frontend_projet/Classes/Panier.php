<?php
class Panier
{
    public function __construct()
    {
        if (!isset($_SESSION['panier'])) {
            $_SESSION['panier'] = [];
        }
    }

    public function ajouterProduit($produitId, $nom, $prix, $note)
    {
        if (isset($_SESSION['panier'][$produitId])) {
            $_SESSION['panier'][$produitId]['quantite']++;
        } else {
            $_SESSION['panier'][$produitId] = [
                'nom' => $nom,
                'prix' => $prix,
                'note' => $note,
                'quantite' => 1
            ];
        }
    }

    public function obtenirPanier()
    {
        return $_SESSION['panier'];
    }

    public function obtenirNombreTotal()
    {
        $total = 0;
        foreach ($_SESSION['panier'] as $produit) {
            $total += $produit['quantite'];
        }
        return $total;
    }

    public function mettreAJourQuantite($produitId, $quantite)
    {
        if (isset($_SESSION['panier'][$produitId])) {
            $_SESSION['panier'][$produitId]['quantite'] = $quantite;
        }
    }

    public function supprimerProduit($produitId)
    {
        if (isset($_SESSION['panier'][$produitId])) {
            unset($_SESSION['panier'][$produitId]);
        }
    }
    public function viderPanier()
    {
        $_SESSION['panier'] = array(); // This clears the cart stored in the session
    }
}
