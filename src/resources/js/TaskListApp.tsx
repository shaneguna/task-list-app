import React from "react"
import ApiService from "./ApiService"

interface TaskList {
    id: number;
    label: string;
    sort_order: number;
    completed_at: any;
}

const TaskListApp = () => {
    const [taskList, setTaskList] = React.useState<TaskList[]>([])
    const [newTask, setNewTask] = React.useState<string>("")
    const [errors, setErrors] = React.useState<string>("")

    React.useEffect(() => {
        // perform an API request to fetch data on mount
        ApiService.getAll()
            .then(json => {
                setTaskList(json)
            })
    }, []);

    // dragItem and dragOverItem references
    const dragItem = React.useRef<any>(null)
    const dragOverItem = React.useRef<any>(null)

    const handleSort: () => void = () => {

        // clone items
        let _taskList = [...taskList]

        // remove and save the dragged item content
        const draggedItemContent = _taskList.splice(dragItem.current, 1)[0]

        // guard, reject draggable
        if (draggedItemContent.completed_at !== null) return;

        // switch dragged item with dragged over item
        _taskList.splice(dragOverItem.current, 0, draggedItemContent)

        let newList = []
        let newListPosition = 0;

        for (let i = 0; i < taskList.length; i++) {
            if (taskList[i] !== _taskList[i]) {
                if (newList.length > 0) {
                    let newItem = _taskList[i].sort_order;
                    let oldItem = newList[newListPosition].sort_order;

                    if (oldItem > newItem) {
                        // swap order
                        _taskList[i].sort_order = oldItem;
                        newList[newListPosition].sort_order = newItem;
                    }
                    newListPosition++;
                }
                newList.push(_taskList[i]);
            }
        }

        ApiService.updateTasks(newList)
            .catch(e => console.error(e))

        dragItem.current = null
        dragOverItem.current = null

        // update main list
        setTaskList(_taskList)
    }

    const handleNameChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        setNewTask(e.target.value)
    }

    const handleAddTask = () => {
        const _taskList: TaskList[] = [...taskList]

        // push to end of list
        let task = {id: 1, label: newTask, sort_order: 1, completed_at: null}

        if (_taskList.length > 0) {
            let ongoing = _taskList.filter(each => each.completed_at === null);

            let lastItem = ongoing[ongoing.length - 1]

            task = {id: lastItem.id + 1, label: newTask, sort_order: lastItem.sort_order + 1, completed_at: null}
        }

        ApiService.addTask(task)
            .then(json => setTaskList(json))
            .catch(e => setErrors(e.message))
    }

    const handleDeleteTask = (index: number, id: number) => {
        let _taskList = [...taskList]

        ApiService.delete(id)
            .catch(e => console.error(e))

        _taskList.splice(index, 1)
        console.log(_taskList)
        setTaskList(_taskList)
    }

    const handleMarkAsCompleted = (id: number) => {
        ApiService.markCompleted(id)
            .then(json => setTaskList(json))
            .catch(e => console.error(e))
    }

    return (
        <div id="task-list" className="h-screen flex items-center flex-col justify-center py-12 sm:px-6 lg:px-8">
            <div className="max-w-md w-fill p-3 bg-white shadow overflow-hidden sm:rounded-lg space-y-8 lg:mx-8">
                <div className="flex justify-center">
                    <h2 className="task-list-heading font-medium">Task List</h2>
                </div>
                {errors && <div className="error">{errors}</div>}
                <div id="input-group" className="flex m-2">
                    <input
                        className="h-10 px-3 py-2 bg-white border shadow-sm border-slate-300 placeholder-slate-400"
                        type="text"
                        name="task"
                        placeholder="ex. Go Bananas"
                        onChange={handleNameChange}
                    />

                    <button id="add-btn"
                            className="h-10 bg-cyan-500 hover:bg-cyan-400 focus:outline-none focus:ring focus:ring-cyan-300 active:bg-cyan-700 px-5 py-2 text-sm leading-5 rounded-tr-lg rounder-br-lg font-semibold"
                            onClick={handleAddTask}>
                        Add Task
                    </button>
                </div>
                <div className="h-80 overflow-x-hidden overflow-y-auto">
                    {taskList.map((task, index) => (
                        <div className="shadow rounded-lg p-3 mt-4 bg-sky-100 flex justify-between">
                            <div
                                key={index}
                                className="flex items-center justify-between w-full"
                                draggable
                                onDragStart={(e) => (dragItem.current = index)}
                                onDragEnter={(e) => (dragOverItem.current = index)}
                                onDragEnd={handleSort}
                                onDragOver={(e) => e.preventDefault()}>
                                <div className="">
                                    <button
                                        className="bg-cyan-500 hover:bg-cyan-600 focus:outline-none focus:ring focus:ring-cyan-300 active:bg-cyan-700 m-2 px-2 py-1 text-sm leading-5 rounded-full font-semibold text-white"
                                        aria-label="Delete a todo" onClick={() => handleDeleteTask(index, task.id)}>
                                        X
                                    </button>
                                </div>
                                <h3 className="ml-2 font-medium text-slate-800 text-sm">{task.label}</h3>
                                <div className="flex justify-end">
                                    <button
                                        className="bg-cyan-500 hover:bg-cyan-600 focus:outline-none focus:ring focus:ring-cyan-300 active:bg-cyan-700 m-2 px-2 py-1 text-sm leading-5 rounded font-semibold text-white"
                                        aria-label="Mark as complete" onClick={() => handleMarkAsCompleted(task.id)}>
                                        {task.completed_at === null ? 'Mark Completed' : 'Completed'}
                                    </button>
                                </div>
                            </div>
                        </div>
                    ))}
                </div>
            </div>
        </div>
    )
}

export default TaskListApp
