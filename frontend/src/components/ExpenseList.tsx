const categoryColors = {
  Housing: { bg: "#EEF2FF", text: "#4338CA", dot: "#6366F1" },
  Food: { bg: "#FEF9C3", text: "#854D0E", dot: "#EAB308" },
  Transport: { bg: "#DCFCE7", text: "#166534", dot: "#22C55E" },
  Health: { bg: "#FCE7F3", text: "#9D174D", dot: "#EC4899" },
  Leisure: { bg: "#FEF3C7", text: "#92400E", dot: "#F59E0B" },
  Other: { bg: "#F3F4F6", text: "#374151", dot: "#9CA3AF" },
};

function CategoryBadge({ category }) {
  const colors = categoryColors[category] || categoryColors.Other;
  return (
    <span
      style={{
        display: "inline-flex",
        alignItems: "center",
        gap: "5px",
        padding: "2px 10px",
        borderRadius: "999px",
        fontSize: "11px",
        fontWeight: 600,
        letterSpacing: "0.04em",
        backgroundColor: colors.bg,
        color: colors.text,
      }}
    >
      <span
        style={{
          width: 6,
          height: 6,
          borderRadius: "50%",
          backgroundColor: colors.dot,
          display: "inline-block",
        }}
      />
      {category}
    </span>
  );
}

export default function ExpenseList({ expenses = [] }) {
  if (expenses.length === 0) {
    return (
      <div
        style={{
          fontFamily: "'DM Sans', 'Segoe UI', sans-serif",
          display: "flex",
          flexDirection: "column",
          alignItems: "center",
          justifyContent: "center",
          padding: "56px 24px",
          color: "#9CA3AF",
          border: "2px dashed #E5E7EB",
          borderRadius: "16px",
          gap: "10px",
        }}
      >
        <svg
          width="40"
          height="40"
          viewBox="0 0 24 24"
          fill="none"
          stroke="#D1D5DB"
          strokeWidth="1.5"
        >
          <path
            d="M9 14l-4-4 4-4M15 10h-4M15 14H9M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
            strokeLinecap="round"
            strokeLinejoin="round"
          />
        </svg>
        <p style={{ margin: 0, fontSize: "15px", fontWeight: 500 }}>
          No expenses yet
        </p>
        <p style={{ margin: 0, fontSize: "13px" }}>
          Add your first expense above
        </p>
      </div>
    );
  }

  const total = expenses.reduce((sum, e) => sum + e.amount, 0);

  return (
    <div style={{ fontFamily: "'DM Sans', 'Segoe UI', sans-serif" }}>
      {/* Summary bar */}
      <div
        style={{
          display: "flex",
          justifyContent: "space-between",
          alignItems: "center",
          marginBottom: "12px",
          padding: "0 2px",
        }}
      >
        <span style={{ fontSize: "13px", color: "#6B7280", fontWeight: 500 }}>
          {expenses.length} expense{expenses.length !== 1 ? "s" : ""}
        </span>
        <span style={{ fontSize: "15px", color: "#111827", fontWeight: 700 }}>
          Total: {total.toLocaleString("fr-FR")} €
        </span>
      </div>

      {/* List */}
      <ul
        style={{
          listStyle: "none",
          margin: 0,
          padding: 0,
          display: "flex",
          flexDirection: "column",
          gap: "8px",
        }}
      >
        {expenses.map((expense) => (
          <li
            key={expense.id}
            style={{
              display: "flex",
              alignItems: "center",
              justifyContent: "space-between",
              padding: "14px 18px",
              borderRadius: "12px",
              backgroundColor: "#FAFAFA",
              border: "1px solid #F3F4F6",
              transition: "box-shadow 0.15s",
            }}
            onMouseEnter={(e) =>
              (e.currentTarget.style.boxShadow = "0 2px 12px rgba(0,0,0,0.07)")
            }
            onMouseLeave={(e) => (e.currentTarget.style.boxShadow = "none")}
          >
            <div
              style={{ display: "flex", flexDirection: "column", gap: "4px" }}
            >
              <span
                style={{ fontSize: "15px", fontWeight: 600, color: "#111827" }}
              >
                {expense.label}
              </span>
              <div
                style={{ display: "flex", alignItems: "center", gap: "8px" }}
              >
                <CategoryBadge category={expense.category} />
                <span style={{ fontSize: "12px", color: "#9CA3AF" }}>
                  {new Date(expense.date).toLocaleDateString("fr-FR", {
                    day: "numeric",
                    month: "short",
                    year: "numeric",
                  })}
                </span>
              </div>
            </div>
            <span
              style={{
                fontSize: "17px",
                fontWeight: 700,
                color: "#1D4ED8",
                whiteSpace: "nowrap",
              }}
            >
              {expense.amount.toLocaleString("fr-FR")} €
            </span>
          </li>
        ))}
      </ul>
    </div>
  );
}
