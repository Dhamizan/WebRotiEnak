<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Sorting Table</title>
    <script>
        function sortTable(columnIndex) {
    let table = document.getElementById("dataTable");
    let tbody = table.getElementsByTagName("tbody")[0];
    let rows = Array.from(tbody.getElementsByTagName("tr"));
    
    let ascending = table.dataset.sortOrder === "asc" ? false : true;
    
    rows.sort((a, b) => {
        let cellA = a.getElementsByTagName("td")[columnIndex].textContent.trim().toLowerCase();
        let cellB = b.getElementsByTagName("td")[columnIndex].textContent.trim().toLowerCase();
        
        if (!isNaN(cellA) && !isNaN(cellB)) {
            return ascending ? cellA - cellB : cellB - cellA;
        }

        return ascending ? cellA.localeCompare(cellB) : cellB.localeCompare(cellA);
    });

    tbody.innerHTML = "";
    rows.forEach(row => tbody.appendChild(row));

    table.dataset.sortOrder = ascending ? "asc" : "desc";
}

    </script>
</head>
<body class="flex justify-center items-center h-screen bg-gray-100">
    <div class="w-3/4 bg-white shadow-lg rounded-lg p-4">
    <table id="dataTable" class="min-w-full leading-normal">
    <thead>
        <tr>
            <th class="py-4 px-6 bg-white font-bold uppercase text-sm text-gray-600 border-b">No</th>
            <th class="py-4 px-6 bg-white font-bold uppercase text-sm text-gray-600 border-b cursor-pointer" onclick="sortTable(1)">
                <div class="flex items-center space-x-4">
                    <span>Outlet</span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5 7.5 3m0 0L12 7.5M7.5 3v13.5m13.5 0L16.5 21m0 0L12 16.5m4.5 4.5V7.5"></path>
                    </svg>
                </div>
            </th>

            <th class="py-4 px-6 bg-white font-bold uppercase text-sm text-gray-600 border-b cursor-pointer" onclick="sortTable(2)">
                <div class="flex items-center space-x-4">
                    <span>Leader Area</span>
                </div>
            </th>

            <th class="py-4 px-6 bg-white font-bold uppercase text-sm text-gray-600 border-b cursor-pointer" onclick="sortTable(3)">
                <div class="flex items-center space-x-4">
                    <span>Phone</span>
                </div>
            </th>

            <th class="py-4 px-6 bg-white font-bold uppercase text-sm text-gray-600 border-b cursor-pointer" onclick="sortTable(4)">
                <div class="flex items-center space-x-4">
                    <span>Address</span>
                </div>
            </th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="py-4 px-6 bg-white">1</td>
            <td class="py-4 px-6 bg-white">Outlet C</td>
            <td class="py-4 px-6 bg-white">John Doe</td>
            <td class="py-4 px-6 bg-white">123456</td>
            <td class="py-4 px-6 bg-white">Jl. ABC</td>
        </tr>
        <tr>
            <td class="py-4 px-6 bg-white">2</td>
            <td class="py-4 px-6 bg-white">Outlet A</td>
            <td class="py-4 px-6 bg-white">Jane Smith</td>
            <td class="py-4 px-6 bg-white">654321</td>
            <td class="py-4 px-6 bg-white">Jl. XYZ</td>
        </tr>
        <tr>
            <td class="py-4 px-6 bg-white">3</td>
            <td class="py-4 px-6 bg-white">Outlet B</td>
            <td class="py-4 px-6 bg-white">Alice Johnson</td>
            <td class="py-4 px-6 bg-white">789012</td>
            <td class="py-4 px-6 bg-white">Jl. DEF</td>
        </tr>
    </tbody>
</table>
    </div>
</body>
</html>
