function departmentLoader(urlTemplate) {
    return {
        departments: [],
        loading: false,

        init() {
            const selectedFaculty = document.getElementById('faculty_id').value;
            if (selectedFaculty) this.loadDepartments(selectedFaculty);
        },

        loadDepartments(facultyId) {
            this.departments = [];
            if (!facultyId) return;
            this.loading = true;
            const url = urlTemplate.replace('__ID__', facultyId);
            fetch(url, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
                .then(r => { if (!r.ok) throw new Error(); return r.json(); })
                .then(data => { this.departments = data; })
                .catch(() => { this.departments = []; })
                .finally(() => { this.loading = false; });
        }
    }
}