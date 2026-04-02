import { render, screen } from "@testing-library/react";
import { describe, it, expect } from "vitest";
import ExpenseList from "../ExpenseList";
import type { Expense } from "../ExpenseList";

describe("ExpenseList", () => {
  const mockExpenses: Expense[] = [
    {
      id: 1,
      label: "Loyer",
      amount: 900,
      date: "2025-01-01",
      category: "Housing",
    },
    {
      id: 2,
      label: "Courses",
      amount: 120,
      date: "2025-01-05",
      category: "Food",
    },
  ];

  it("affiche toutes les dépenses passées en props", () => {
    render(<ExpenseList expenses={mockExpenses} />);

    expect(screen.getByText("Loyer")).toBeInTheDocument();
    expect(screen.getByText("Courses")).toBeInTheDocument();
  });

  it("affiche un message quand la liste est vide", () => {
    render(<ExpenseList expenses={[]} />);

    expect(screen.getByText(/no expenses/i)).toBeInTheDocument();
  });
});
