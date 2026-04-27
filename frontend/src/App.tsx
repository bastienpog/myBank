import ExpenseForm from "./components/ExpenseForm";
import ExpenseList from "./components/ExpenseList";
import { useState } from "react";
import type { Expense } from "./components/ExpenseList";

export default function App() {
  const [expenses, setExpenses] = useState<Expense[]>([]);

  return (
    <main
      className="page-container"
      style={{ padding: "2rem", maxWidth: 700, margin: "0 auto" }}
    >
      <h1>MyBank</h1>
      <ExpenseForm onAdd={(e) => setExpenses((prev) => [...prev, e])} />
      <ExpenseList expenses={expenses} />
    </main>
  );
}
