import React from 'react';
import { render, fireEvent } from '@testing-library/react';
import TaskListApp from '../TaskListApp';
import ApiService from "../ApiService";

jest.mock("../ApiService");

describe("handleAddTask", () => {
    it("adds a new task to the task list", async () => {
        ApiService.addTask.mockResolvedValue({
            id: 1,
            label: "New Task",
            sort_order: 1,
            completed_at: null,
        });

        ApiService.getAll.mockResolvedValue([]);

        const { getByPlaceholderText, getByText } = render(<TaskListApp />);

        const input = getByPlaceholderText("ex. Go Bananas");
        const addButton = getByText("Add Task");

        fireEvent.change(input, { target: { value: "New Task" } });
        fireEvent.click(addButton);

        const expectedTask = {
            id: 1,
            label: "New Task",
            sort_order: 1,
            completed_at: null,
        };

        expect(ApiService.addTask).toHaveBeenCalledWith(expectedTask);
    });
});
