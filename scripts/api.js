window.addEventListener('load', function() {
    fetch('/phpBasico/api.php')
        .then(response => response.json())
        .then(proyectos => {
            console.log('Proyectos recibidos:', proyectos);
            const proyectosContainer = document.getElementById('proyectos-container');
            
            if (proyectosContainer && Array.isArray(proyectos)) {
                // Crear el contenedor principal con fila de Bootstrap
                const row = document.createElement('div');
                row.className = 'row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4';
                
                // Para cada proyecto, crear una tarjeta
                proyectos.forEach(proyecto => {
                    const col = document.createElement('div');
                    col.className = 'col';
                    
                    const card = document.createElement('div');
                    card.className = 'card h-100';
                    
                    // Asumiendo que cada proyecto tiene propiedades como 'name', 'description', etc.
                    // Ajusta según la estructura real de tus datos
                    card.innerHTML = `
                        <div class="card-body">
                            <h5 class="card-title">${proyecto.name || 'Sin nombre'}</h5>
                            ${proyecto.description ? `<p class="card-text">${proyecto.description}</p>` : ''}
                        </div>
                        <div class="card-footer bg-transparent">
                            <small class="text-muted">ID: ${proyecto.id || 'N/A'}</small>
                        </div>
                    `;
                    
                    col.appendChild(card);
                    row.appendChild(col);
                });
                
                // Limpiar el contenedor y agregar la fila
                proyectosContainer.innerHTML = '';
                proyectosContainer.appendChild(row);
                
            }
        })
        
});