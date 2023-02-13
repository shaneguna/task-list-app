export default class ApiService {
    static async getAll() {
        return await fetch("http://localhost:8000/api/tasks/all")
            .then(response => response.json())
            .then(json => json)
            .catch(e => console.error(e));
    }

    static async updateTasks(newList: any) {
        return await fetch("http://localhost:8000/api/tasks/update", {
            method: "PUT",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(newList)
        })
            .then(response => response.json());
    }

    static async delete(id: number) {
        return await fetch(`http://localhost:8000/api/tasks/delete/${id}`, {
            method: "DELETE",
            headers: {
                "Content-Type": "application/json"
            },
        })
            .then(response => response.json());
    }

    static async markCompleted(id: number) {
        return await fetch(`http://localhost:8000/api/tasks/completed/${id}`, {
            method: "PUT",
            headers: {
                "Content-Type": "application/json"
            },
        })
            .then(response => response.json());
    }

    static async addTask(task: any) {
        return await fetch("http://localhost:8000/api/tasks/add", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(task)
        })
            .then(response => {
                if (!response.ok) {
                    if (response.status === 422) {
                        return response.json().then(json => {

                            let errorMessage = "";
                            const validationErrors = json.data;

                            if (validationErrors) {
                                for (const key in validationErrors) {
                                    if (validationErrors.hasOwnProperty(key)) {
                                        errorMessage += `\n ${validationErrors[key].join(", ")}`;
                                    }
                                }
                            }

                            throw new Error(errorMessage);
                        });
                    }

                    throw new Error(response.statusText)
                }

                return response.json()
            })
            .then(json => json);
    }
}
