import { useState } from "react";
import type { CSSProperties, FocusEvent } from "react";
import type { Expense } from "./ExpenseList";

interface ExpenseFormProps {
  onAdd?: (expense: Expense) => void;
}

type Category =
  | "Housing"
  | "Food"
  | "Transport"
  | "Health"
  | "Leisure"
  | "Other";

const CATEGORIES: Category[] = [
  "Housing",
  "Food",
  "Transport",
  "Health",
  "Leisure",
  "Other",
];

const inputStyle: CSSProperties = {
  width: "100%",
  padding: "10px 14px",
  border: "1.5px solid #E5E7EB",
  borderRadius: 10,
  fontSize: 14,
  fontFamily: "'DM Sans', 'Segoe UI', sans-serif",
  color: "#111827",
  backgroundColor: "#FAFAFA",
  outline: "none",
  boxSizing: "border-box",
  transition: "border-color 0.15s",
};

const labelStyle: CSSProperties = {
  display: "block",
  fontSize: 12,
  fontWeight: 600,
  color: "#374151",
  marginBottom: 5,
  letterSpacing: "0.04em",
  textTransform: "uppercase",
};

function focusOn(e: FocusEvent<HTMLInputElement | HTMLSelectElement>) {
  e.target.style.borderColor = "#6366F1";
}
function focusOff(e: FocusEvent<HTMLInputElement | HTMLSelectElement>) {
  e.target.style.borderColor = "#E5E7EB";
}

export default function ExpenseForm({ onAdd }: ExpenseFormProps) {
  const [label, setLabel] = useState<string>("");
  const [amount, setAmount] = useState<string>("");
  const [date, setDate] = useState<string>("");
  const [category, setCategory] = useState<Category>("Other");

  function handleSubmit(): void {
    if (!label || !amount) return;

    const newExpense: Expense = {
      id: Date.now(),
      label,
      amount: parseFloat(amount),
      date: date || new Date().toISOString().slice(0, 10),
      category,
    };

    onAdd?.(newExpense);
    setLabel("");
    setAmount("");
    setDate("");
    setCategory("Other");
  }

  return (
    <div
      style={{
        fontFamily: "'DM Sans', 'Segoe UI', sans-serif",
        backgroundColor: "#fff",
        border: "1.5px solid #E5E7EB",
        borderRadius: 16,
        padding: 24,
        display: "flex",
        flexDirection: "column",
        gap: 16,
      }}
    >
      <h2
        style={{ margin: 0, fontSize: 17, fontWeight: 700, color: "#111827" }}
      >
        New Expense
      </h2>

      {/* Label */}
      <div>
        <label htmlFor="expense-label" style={labelStyle}>
          Label
        </label>
        <input
          id="expense-label"
          type="text"
          placeholder="e.g. Groceries"
          value={label}
          onChange={(e) => setLabel(e.target.value)}
          style={inputStyle}
          onFocus={focusOn}
          onBlur={focusOff}
        />
      </div>

      {/* Amount — label text matches /amount/i */}
      <div>
        <label htmlFor="expense-amount" style={labelStyle}>
          Amount
        </label>
        <input
          id="expense-amount"
          type="number"
          placeholder="0.00"
          min={0}
          step={0.01}
          value={amount}
          onChange={(e) => setAmount(e.target.value)}
          style={inputStyle}
          onFocus={focusOn}
          onBlur={focusOff}
        />
      </div>

      {/* Date + Category */}
      <div style={{ display: "flex", gap: 12 }}>
        <div style={{ flex: 1 }}>
          <label htmlFor="expense-date" style={labelStyle}>
            Date
          </label>
          <input
            id="expense-date"
            type="date"
            value={date}
            onChange={(e) => setDate(e.target.value)}
            style={inputStyle}
            onFocus={focusOn}
            onBlur={focusOff}
          />
        </div>
        <div style={{ flex: 1 }}>
          <label htmlFor="expense-category" style={labelStyle}>
            Category
          </label>
          <select
            id="expense-category"
            value={category}
            onChange={(e) => setCategory(e.target.value as Category)}
            style={{ ...inputStyle, cursor: "pointer" }}
            onFocus={focusOn}
            onBlur={focusOff}
          >
            {CATEGORIES.map((cat) => (
              <option key={cat} value={cat}>
                {cat}
              </option>
            ))}
          </select>
        </div>
      </div>

      {/* Submit — name matches /add/i */}
      <button
        type="button"
        onClick={handleSubmit}
        style={{
          padding: 12,
          borderRadius: 10,
          border: "none",
          backgroundColor: "#4F46E5",
          color: "#fff",
          fontSize: 14,
          fontWeight: 700,
          cursor: "pointer",
          letterSpacing: "0.03em",
          transition: "background-color 0.15s, transform 0.1s",
          fontFamily: "'DM Sans', 'Segoe UI', sans-serif",
        }}
        onMouseEnter={(e) =>
          (e.currentTarget.style.backgroundColor = "#4338CA")
        }
        onMouseLeave={(e) =>
          (e.currentTarget.style.backgroundColor = "#4F46E5")
        }
        onMouseDown={(e) => (e.currentTarget.style.transform = "scale(0.98)")}
        onMouseUp={(e) => (e.currentTarget.style.transform = "scale(1)")}
      >
        Add Expense
      </button>
    </div>
  );
}
