import React from "react"
import ReactDOM from "react-dom/client"
import TaskListApp from "./TaskListApp";

const root = ReactDOM.createRoot(document.getElementById("app") as HTMLElement)
root.render(
    <React.StrictMode>
        <TaskListApp />
    </React.StrictMode>,
)
