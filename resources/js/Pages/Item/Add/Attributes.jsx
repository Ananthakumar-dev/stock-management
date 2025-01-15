import { useState, useEffect } from "react";

const Attributes = ({ errors, setErrors }) => {
    const [attributes, setAttributes] = useState([]);
    const [selectedAttributes, setSelectedAttributes] = useState([]);

    useEffect(() => {
        const fetchData = async () => {
            const getAttributes = route("attributes.get");
            const attributesResponse = await axios.get(getAttributes);

            setAttributes(attributesResponse.data?.attributes);
        };
        fetchData();
    }, []);

    const addAttribute = () => {
        setSelectedAttributes([...selectedAttributes, { id: "", value: "" }]);
    };

    const removeAttribute = (index) => {
        const updatedAttributes = [...selectedAttributes];
        updatedAttributes.splice(index, 1);
        setSelectedAttributes(updatedAttributes);
    };

    const handleAttributeChange = (index, field, value) => {
        // Prevent duplicate attribute selection
        if (field === "id") {
            const isDuplicate = selectedAttributes.some(
                (attr, i) => attr.id === value && i !== index
            );

            if (isDuplicate) {
                setErrors({
                    ...errors,
                    [`attributes.${index}.id`]: [
                        "This attribute is already selected.",
                    ],
                });
                return;
            }
        }

        // Clear errors for the current field if fixed
        if (errors[`attributes.${index}.${field}`]) {
            const updatedErrors = { ...errors };
            delete updatedErrors[`attributes.${index}.${field}`];
            setErrors(updatedErrors);
        }

        // Update attributes
        const updatedAttributes = [...selectedAttributes];
        updatedAttributes[index][field] = value;
        setSelectedAttributes(updatedAttributes);
    };

    return (
        <div className="mb-4">
            <label className="block text-sm font-medium text-gray-700">
                Attributes
            </label>
            {selectedAttributes.map((attr, index) => (
                <div key={index} className="flex items-center mb-2">
                    <select
                        value={attr.id}
                        onChange={(e) =>
                            handleAttributeChange(index, "id", e.target.value)
                        }
                        name={`attributes[${index}][id]`}
                        className={`border ${
                            errors[`attributes.${index}.id`]
                                ? "border-red-500"
                                : "border-gray-300"
                        } rounded px-4 py-2 mr-2`}
                    >
                        <option value="">Select Attribute</option>
                        {attributes.map((attribute) => (
                            <option key={attribute.id} value={attribute.id}>
                                {attribute.name}
                            </option>
                        ))}
                    </select>
                    <input
                        type="text"
                        value={attr.value}
                        onChange={(e) =>
                            handleAttributeChange(
                                index,
                                "value",
                                e.target.value
                            )
                        }
                        name={`attributes[${index}][value]`}
                        placeholder="Value"
                        className={`border ${
                            errors[`attributes.${index}.value`]
                                ? "border-red-500"
                                : "border-gray-300"
                        } rounded px-4 py-2 mr-2`}
                    />
                    <button
                        type="button"
                        onClick={() => removeAttribute(index)}
                        className="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600"
                    >
                        Remove
                    </button>
                    {errors[`attributes.${index}.id`] && (
                        <p className="text-red-500 text-sm">
                            {errors[`attributes.${index}.id`][0]}
                        </p>
                    )}
                    {errors[`attributes.${index}.value`] && (
                        <p className="text-red-500 text-sm">
                            {errors[`attributes.${index}.value`][0]}
                        </p>
                    )}
                </div>
            ))}

            {selectedAttributes.length < attributes.length && (
                <button
                    type="button"
                    onClick={addAttribute}
                    className="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
                >
                    Add Attribute
                </button>
            )}
        </div>
    );
};

export default Attributes;
