document.addEventListener('DOMContentLoaded', function() {
    const updateUser = (userId, btn) => {
        const row = btn.closest('tr');
        const username = row.cells[1].innerText.trim();
        const email = row.cells[2].innerText.trim();

        fetch('../../functions/Admin/update_user.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id: userId, username: username, email: email })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Utilisateur mis à jour avec succès!');
                window.location.reload();
            } else {
                alert('Erreur lors de la mise à jour de l\'utilisateur : ' + data.message);
            }
        });
    }

    const deleteUser = (userId) => {
        if (confirm('Voulez-vous vraiment supprimer cet utilisateur?')) {
            fetch('../../functions/Admin/delete_user.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: userId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Utilisateur supprimé avec succès!');
                    window.location.reload();
                } else {
                    alert('Erreur lors de la suppression de l\'utilisateur : ' + data.message);
                }
            });
        }
    }

    const updateProduct = (productId, btn) => {
        const row = btn.closest('tr');
        const nom = row.cells[1].innerText.trim();
        const prix = parseFloat(row.cells[2].innerText.replace('€', '').trim());
        const note = parseInt(row.cells[4].innerText.trim());

        fetch('../../functions/Admin/update_produit.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id: productId, nom: nom, prix: prix, note: note })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Produit mis à jour avec succès!');
                window.location.reload();
            } else {
                alert('Erreur lors de la mise à jour du produit : ' + data.message);
            }
        });
    }

    const deleteProduct = (productId) => {
        if (confirm('Voulez-vous vraiment supprimer ce produit?')) {
            fetch('../../functions/Admin/delete_produit.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: productId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Produit supprimé avec succès!');
                    window.location.reload();
                } else {
                    alert('Erreur lors de la suppression du produit : ' + data.message);
                }
            });
        }
    }

    // Attacher les fonctions à l'objet window pour y accéder dans le code HTML
    window.updateUser = updateUser;
    window.deleteUser = deleteUser;
    window.updateProduct = updateProduct;
    window.deleteProduct = deleteProduct;
});
